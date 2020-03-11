<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CommentRequest;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ]);

        if ($request->has('images')) {
            $this->storeImages($request->file('images'), $comment);
        }

        return response()->json([
            'ok' => true,
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
