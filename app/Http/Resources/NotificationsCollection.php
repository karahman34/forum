<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationsCollection extends ResourceCollection
{
    /**
     * Users Emmitter
     *
     * @var Illuminate\Support\Collection
     */
    public $users;

    public function __construct($resource, object $users)
    {
        parent::__construct($resource);
        $this->users = collect($users);
    }

    private function getNotificationMessage(string $action_type, User $user)
    {
        $message = '';

        if ($action_type === 'comment') {
            $message = "{$user->username} commented on your post.";
        }

        return $message;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($notification) {
            $user_emitter = $this->users->firstWhere('id', $notification['data']['from_user_id']);
            $message = $this->getNotificationMessage($notification['data']['action_type'], $user_emitter);

            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'notifiable_id' => $notification->notifiable_id,
                'notifiable_type' => $notification->notifiable_type,
                'data' => [
                    'post_id' => $notification['data']['post_id'],
                    'href' => $notification['data']['href'],
                    'message' => $message,
                    'user_emitter' => [
                        'id' => $user_emitter->id,
                        'avatar' => $user_emitter->getAvatar(),
                        'username' => $user_emitter->username,
                    ],
                ],
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at->diffForHumans(),
                'updated_at' => $notification->updated_at->diffForHumans(),
            ];
        });
    }
}
