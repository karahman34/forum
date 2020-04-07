<?php

namespace App\Jobs;

use App\NotifCount;
use App\Notification;
use App\Post;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class DeletePostNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Post
     *
     * @var Post
     */
    public $post;

    /**
     *  
     * @var User $auth
     */
    public $auth;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
        $this->auth = Auth::user();
    }

    /**
     * Decrement user notif count
     *
     * @return  void
     */
    private function decrementNotifCount()
    {
        $notif = NotifCount::where('user_id', $this->post->author->id)->first();
        if ($notif && $notif->count > 0) {
            $notif->update([
                'count' => $notif->count - 1,
            ]);
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Delete user notification
        $notification = Notification::where('data->post_id', $this->post->id)
            ->where('data->from_user_id', $this->auth->id)
            ->first();

        if ($notification) {
            $notification->delete();
        }

        // Decrement user notif count
        $this->decrementNotifCount();
    }
}
