<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'post_id' => $this->post_id,
            'body' => $this->body,
            'pinned' => $this->pinned,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'images' => $this->images->map(function ($image) {
                return [
                    'src' => $image->getImage(),
                    'original' => $image->src,
                ];
            }),
            'user' => [
                'id' => $this->user->id,
                'avatar' => $this->user->getAvatar(),
                'username' => $this->user->username,
            ]
        ];
    }
}
