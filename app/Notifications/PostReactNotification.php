<?php

namespace App\Notifications;

use App\NotifCount;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class PostReactNotification extends Notification
{
    use Queueable;

    /**
     * Post Id
     *
     * @var int
     */
    public $postId;

    /**
     * Action type
     *
     * @var string
     */
    public $action_type;

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
    public function __construct(int $postId, string $action_type)
    {
        $this->postId = $postId;
        $this->action_type = $action_type;
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
            ['user_id' => $this->from->id],
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
            'post_id' => $this->postId,
            'action_type' => $this->action_type,
            'href' => route('post.show', ['id' => $this->postId]),
        ];
    }
}
