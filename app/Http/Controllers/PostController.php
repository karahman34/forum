<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Post;
use App\PostImage;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get QS Options
        $limit = $request->get('limit', 15);

        $posts = Post::with(['author:id,username,avatar', 'tags:name'])->paginate($limit);

        return view('welcome', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create Post';
        $method = 'POST';
        $action = route('post.store');

        return view('posts.form', compact('method', 'action', 'title'));
    }

    /**
     * Store post image.
     *
     * @param   array  $images
     *
     * @return  array  $imageNames
     */
    private function storeImages(array $images)
    {
        $imageNames = [];
        foreach ($images as $image) {
            $imageNames[] = [
                'image' => $image->store('/img/posts')
            ];
        }

        return $imageNames;
    }

    /**
     * Sync post tags
     *
     * @param Request $request
     * @param Post    $post
     *
     * @return void
     */
    private function syncTags(Request $request, Post $post)
    {
        // Get Tags
        $tags = $request->get('tags');
        // Get Tags in db
        $tagsInDB = Tag::select('id', 'name')->whereIn('name', $tags)->get();

        $selectedTagIds = [];
        foreach ($tags as $tag) {
            $t = $tagsInDB->firstWhere('name', $tag);
            if (!$t) {
                $t = Tag::create([
                    'name' => $tag,
                ]);
            }

            $selectedTagIds[] =  $t->id;
        }

        // Sync post tags
        $post->tags()->sync($selectedTagIds);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        // Create Post
        $payload = $request->only('title', 'body');
        $payload['user_id'] = Auth::id();
        $post = Post::create($payload);

        // Sync tags
        $this->syncTags($request, $post);

        if ($request->has('images')) {
            $imageNames = $this->storeImages($request->file('images'));

            // Associate post images
            $post->images()->createMany($imageNames);
        }

        // Set flash message
        $request->session()->flash('success', 'Success to create Post.');

        $nextUrl = route('post.show', ['id' => $post->id]);
        return response()->json(['next_url' => $nextUrl], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        // Get the post
        $post = Post::with(['author:id,avatar,username', 'tags:name', 'images:post_id,image'])->findOrFail($id);
        $title = $post->title;

        return view('posts.show', compact('post', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        // Get the post
        $post = Post::with(['tags:tags.name', 'images:post_id,image'])->findOrFail($id);

        // Check authorization
        $this->authorize('update', $post);

        // Set the title & form
        $title = "Update {$post->title}";
        $method = 'PUT';
        $action = route('post.update', ['id' => $post->id]);

        return view('posts.form', compact('method', 'action', 'post', 'title'));
    }

    /**
     * Delete Post Image
     *
     * @param   string|array  $image
     *
     * @return  void
     */
    private function deletePostImage($image)
    {
        Storage::delete($image);
    }

    /**
     * Sync old Post images
     *
     * @param   Request  $request
     * @param   Post     $post
     *
     * @return  void
     */
    private function syncOldImages(Request $request, Post $post)
    {
        $oldImages = $request->get('old_images', null);
        if ($oldImages !== null && count($oldImages) > 0) {
            $postImages = $post->images()->get();

            $imageWillDelete = [];
            foreach ($postImages as $postImage) {
                if (!in_array($postImage->image, $oldImages)) {
                    $imageWillDelete[] = $postImage->image;
                }
            }

            if (count($imageWillDelete) > 0) {
                // Delete post image from storage
                $this->deletePostImage($imageWillDelete);

                // Delete post image in db
                PostImage::whereIn('image', $imageWillDelete)->delete();
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PostRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, $id)
    {
        // Get payload
        $payload = $request->only('title', 'body');

        // Get post
        $post = Post::findOrFail($id);

        // Check authorization
        $this->authorize('update', $post);

        // Update Post
        $post->update($payload);

        // Sync post tags
        $this->syncTags($request, $post);

        // Sync old images
        $this->syncOldImages($request, $post);

        // Upload images
        if ($request->has('images')) {
            $imageNames = $this->storeImages($request->file('images'));
            $post->images()->createMany($imageNames);
        }

        // Set flash message
        $request->session()->flash('success', 'Success to update Post.');

        $nextUrl = route('post.show', ['id' => $post->id]);
        return response()->json(['next_url' => $nextUrl], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get the post
        $post = Post::select('id')->where('id', $id)->findOrFail();

        // Check authorization
        $this->authorize('update', $post);
        
        if ($post->delete()) {
            $this->deletePostImage($post->image->pluck('image'));
        }

        session()->flash('success', 'Success to delete post.');

        return back();
    }
}
