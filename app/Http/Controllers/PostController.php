<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
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
}
