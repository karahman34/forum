<?php

namespace App\Http\Controllers;

use App\Http\Resources\UsersCollection;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->get('limit', 15);
        $q = $request->get('q', null);

        $query = User::select('id', 'username', 'avatar')
            ->withCount(['posts']);

        if ($q !== null) {
            $query->where('username', 'like', "%{$q}%");
        }

        $users = $query->paginate($limit);

        return (new UsersCollection($users))
            ->additional([
                'ok' => true,
            ]);
    }

    /**
     * Get user by username
     *
     * @param   string  $username
     *
     * @return  \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(string $username)
    {
        $user = User::select('id', 'avatar', 'username', 'created_at')
            ->where('username', $username)
            ->firstOrFail();

        $posts = $user->posts()
            ->with(['tags:name'])
            ->withCount(['comments', 'seen'])
            ->paginate(12);

        $title = $user->username . " Profile";

        return view('users.profile', compact('user', 'posts', 'title'));
    }

    /**
     * Get user saved posts.
     *
     * @return  \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getSavedPosts()
    {
        $user = auth()->user();

        // Authorize
        $this->authorize('savedPosts', $user);

        $saved_posts = $user->savedPosts()
            ->select('posts.id', 'posts.user_id', 'title', 'posts.created_at')
            ->with(['author:id,username,avatar', 'tags:name'])
            ->paginate(14);

        foreach ($saved_posts as &$post) {
            $post['saved'] = 1;
        }

        return view('users.saved_posts', ['title' => 'Saved Posts', 'posts' => $saved_posts]);
    }

    /**
     * Show edit User form
     *
     * @param   string  $username
     *
     * @return  \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(string $username)
    {
        $user = User::select('id', 'avatar', 'email', 'username')
            ->where('username', $username)
            ->firstOrFail();

        // Authorize
        $this->authorize('update', $user);

        $title = 'Edit ' . $user->username;

        return view('users.edit', compact('user', 'title'));
    }

    /**
     * Update User Avatar
     *
     * @param   Request  $request
     * @param   User     $user
     *
     * @return  string   $avatarName
     */
    private function updateUserAvatar(Request $request, User $user)
    {
        // Put avatar to storage
        $avatarName = $request->file('avatar')->store('img/avatars');

        // Remove old user avatar
        if ($user->avatar !== null) {
            $userCurrentAvatar = $user->avatar;

            // Remove from storage
            Storage::delete($userCurrentAvatar);
        }

        return $avatarName;
    }

    /**
     * Update User Profile
     *
     * @param   Request  $request
     * @param   int      $id
     *
     * @return  \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'avatar' => 'image|mimes:jpg,jpeg,png,gif|max:4096',
        ]);

        // Get user
        $user = User::select('id', 'username', 'email', 'avatar')
            ->where('id', $id)
            ->firstOrFail();

        // Authorize
        $this->authorize('update', $user);

        // Get the payload
        $payload = $request->only('username', 'email');

        // Update Avatar
        if ($request->has('avatar')) {
            $avatar = $this->updateUserAvatar($request, $user);

            $payload['avatar'] = $avatar;
        }

        // Update user
        $user->update($payload);

        return redirect()->route('user.edit', ['username' => $user->username])->with('success', 'Success to update user.');
    }

    /**
     * Show edit password form.
     *
     * @param   string  $username
     *
     * @return  \Illuminate\View\View
     */
    public function editPassword(string $username)
    {
        // Get User
        $user = User::select('id', 'username')->where('username', $username)->firstOrFail();
        // Authorize
        $this->authorize('update', $user);

        // Set window title
        $title = 'Update Password';

        return view('users.edit_password', compact('title', 'username'));
    }

    /**
     * Update user password.
     *
     * @param   Request  $request
     * @param   string   $username
     *
     * @return  \Illuminate\Routing\Redirector
     */
    public function updatePassword(Request $request, string $username)
    {
        $payload = $request->validate([
            'old_password' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:255|confirmed',
        ]);

        // Get user
        $user = User::select('id', 'password')->where('username', $username)->firstOrFail();

        // Authorize
        $this->authorize('update', $user);

        // Make password visible
        $user = $user->makeVisible('password');

        // Check old password
        if (!Hash::check($payload['old_password'], $user->password)) {
            return redirect()->back()->with('failed', 'The old password is not match.');
        }

        // Update password
        $user->update([
            'password' => Hash::make($payload['password']),
        ]);

        return redirect()->back()->with('success', 'Success to update password.');
    }
}
