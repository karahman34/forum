<?php

namespace App\Jobs;

use App\Notification;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeletePostNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     *  
     * @var int
     */
    public $postId;

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
    public function __construct(int $postId, User $auth)
    {
        $this->postId = $postId;
        $this->auth = $auth;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notification = Notification::where('data->post_id', $this->postId)
            ->where('data->from_user_id', $this->auth->id)
            ->firstOrFail();

        $notification->delete();
    }
}
