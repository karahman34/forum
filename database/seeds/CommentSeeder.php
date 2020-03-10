<?php

use App\Post;
use App\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all posts
        $posts = Post::select('id')->get();

        // Get All users
        $users = User::select('id')->get();
        $userIds = $users->pluck('id');

        $posts->map(function ($post) use ($userIds) {
            $howManyComments = rand(12, 32);
            
            $comments = [];
            for ($i=1; $i <= $howManyComments; $i++) {
                $comments[] = [
                    'user_id'=> $userIds->random(),
                    'body' => 'Comment ke-' . $i,
                ];
            }

            // Create comments
            $post->comments()->createMany($comments);
        });
    }
}
