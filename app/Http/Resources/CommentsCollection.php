<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($comment) {
            return [
                'id' => $comment->id,
                'user_id' => $comment->user_id,
                'post_id' => $comment->post_id,
                'body' => $comment->body,
                'created_at' => $comment->created_at->diffForHumans(),
                'updated_at' => $comment->updated_at->diffForHumans(),
                'images' => $comment->images->map(function ($image) {
                    return [
                        'src' => $image->getImage(),
                        'original' => $image->url,
                    ];
                }),
                'user' => [
                    'id' => $comment->user->id,
                    'avatar' => $comment->user->getAvatar(),
                    'username' => $comment->user->username,
                ],
            ];
        });
    }
}
