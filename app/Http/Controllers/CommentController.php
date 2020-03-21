<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CommentRequest;
use App\Image;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    /**
     * Store commenet images.
     *
     * @param   array   $images
     * @param   Comment  $comment
     *
     * @return  void
     */
    private function storeImages($images, Comment $comment)
    {
        $uploadedNames = [];
        foreach ($images as $image) {
            // Store image in storage
            $uploadedNames[] = [
                'url' => $image->store('img/comments'),
            ];
        }

        // Insert image url to DB
        $comment->images()->createMany($uploadedNames);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request, int $postId)
    {
        // Get Post
        $post = Post::select('id')->where('id', $postId)->firstOrFail();

        // Create Comment
        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->get('body'),
            'pinned' => 'n',
        ]);

        // Store images
        if ($request->has('images')) {
            $this->storeImages($request->file('images'), $comment);
        }

        // Get the user
        $auth = auth()->user();
        $comment['user'] = [
            'id' => $auth->id,
            'username' => $auth->username,
            'avatar' => $auth->getAvatar(),
        ];

        return response()->json([
            'ok' => true,
            'data' => $comment,
        ], 201);
    }

    /**
     * Update comment pin.
     *
     * @param   int     $id
     * @param   string  $val
     *
     * @return  Boolean
     */
    private function updatePin(int $id, string $val)
    {
        $comment = Comment::select('id')
                            ->where('id', $id)
                            ->firstOrFail();
        return $comment->update([
            'pinned' => $val,
        ]);
    }

    /**
     * Pin Comment.
     *
     * @param   int  $id
     *
     * @return  \Illuminate\Http\Response
     */
    public function pin(int $id)
    {
        $this->updatePin($id, 'y');

        return response()->json([
            'ok' => true,
            'message' => 'Comment pinned.',
        ], 202);
    }

    /**
     * Unpin Comment.
     *
     * @param   int  $id
     *
     * @return  \Illuminate\Http\Response
     */
    public function unpin(int $id)
    {
        $this->updatePin($id, 'n');

        return response()->json([
            'ok' => true,
            'message' => 'Comment unpinned.',
        ], 202);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comment = Comment::with('images')->findOrFail($id);

        // Check Authorization
        $this->authorize('update', $comment);

        $title = 'Edit Comment';

        return view('comments.edit', compact('comment', 'title'));
    }

    /**
     * Delete comment images
     *
     * @param   array  $images
     *
     * @return  void
     */
    private function deleteCommentImages(array $images)
    {
        // Delete from local storage
        Storage::delete($images);

        // Delete from DB
        Image::whereIn('url', $images)->delete();
    }

    /**
     * Sync comment old images
     *
     * @param   Request  $request
     * @param   Comment  $comment
     *
     * @return  void
     */
    private function syncOldImages(Request $request, Comment $comment)
    {
        // Get old images
        $old_images = $request->get('old_images', []);

        // Get comment images
        $comment_images = $comment->images->pluck('url');

        // Contains images to be deleted
        $imageWillDelete = [];

        if (count($old_images) > 0) {
            foreach ($comment_images as $comment_image) {
                if (!in_array($comment_image, $old_images)) {
                    $imageWillDelete[] = $comment_image;
                }
            }
        } else {
            $imageWillDelete = $comment_images->toArray();
        }

        // Delete Comment images
        $this->deleteCommentImages($imageWillDelete);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CommentRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommentRequest $request, $id)
    {
        // Get Comment
        $comment = Comment::select('id', 'user_id')->where('id', $id)->firstOrFail();

        // Check Authorization
        $this->authorize('update', $comment);

        // Get payload
        $payload = [
            'body' => $request->get('body'),
        ];

        // Update comment
        $comment->update($payload);

        // Sync old images
        $this->syncOldImages($request, $comment);

        // Store images
        if ($request->has('images')) {
            $this->storeImages($request->file('images'), $comment);
        }

        return response()->json([
            'ok' => true,
        ], 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get comment
        $comment = Comment::select('id', 'user_id')->where('id', $id)->firstOrFail();

        // Check Authorization
        $this->authorize('delete', $comment);

        if ($comment->delete()) {
            // Delete comment images
            $this->deleteCommentImages($comment->images->pluck('url')->toArray());

            return response()->json([
                'ok' => true,
            ], 202);
        }

        return response()->json([
            'ok' => false,
        ], 500);
    }
}
