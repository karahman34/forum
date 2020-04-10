<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PostsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($post) {
            return [
                'id' => $post->id,
                'user_id' => $post->user_id,
                'title' => $post->title,
                'created_at' => $post->created_at->diffForHumans(),
                'updated_at' => $post->updated_at->diffForHumans(),
                'author' => [
                    'id' => $post->author->id,
                    'username' => $post->author->username,
                    'avatar' => $post->author->getAvatar(),
                ],
                'tags' => $post->tags->map(function ($tag) {
                    return $tag->name;
                }),
                'seens_count' => $post->seens_count,
                'comments_count' => $post->comments_count,
            ];
        });
    }
}
