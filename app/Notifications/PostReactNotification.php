<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostReactNotification extends Notification
{
    use Queueable;

    /**
     * Post Id
     *
     * @var int $postId
     */
    public $postId;

    /**
     * User that fire the event
     *
     * @var User $from
     */
    public $from;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(int $postId, User $from)
    {
        $this->postId = $postId;
        $this->from = $from;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'from_user_id' => $this->from->id,
            'post_id' => $this->postId,
            'href' => route('post.show', ['id' => $this->postId]),
        ];
    }
}
