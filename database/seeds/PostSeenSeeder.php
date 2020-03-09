<?php

use App\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all posts
        $posts = App\Post::select('id')->get();

        // Define Payloads
        $payload = $posts->map(function ($post) {
            return [
                'post_id' => $post->id,
                'count' => rand(0, 200),
            ];
        });

        // Insert
        DB::table('post_seens')->insert($payload->toArray());
    }
}
