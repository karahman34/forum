<?php

namespace App\Notifications;

use App\NotifCount;
use App\Post;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class PostReactNotification extends Notification
{
    use Queueable;

    /**
     * Post
     *
     * @var Post
     */
    public $post;

    /**
     * Action type
     *
     * @var string
     */
    public $action_type;

    /**
     * Action ID
     *
     * @var int|string
     */
    public $action_id;

    /**
     * User that fire the event
     *
     * @var User
     */
    public $from;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Post $post, string $action_type, int $action_id)
    {
        $this->post = $post;
        $this->action_type = $action_type;
        $this->action_id = $action_id;
        $this->from = Auth::user();
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

    private function incrementNotifCount()
    {
        $notif = NotifCount::firstOrCreate(
            ['user_id' => $this->post->author->id],
            ['count' => 0]
        );

        $notif->increment('count');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $this->incrementNotifCount();

        return [
            'from_user_id' => $this->from->id,
            'post_id' => $this->post->id,
            'action_id' => $this->action_id,
            'action_type' => $this->action_type,
            'href' => route('post.show', ['id' => $this->post->id]),
        ];
    }
}
