<?php

use App\Post;
use App\PostImage;
use Illuminate\Database\Seeder;

class PostImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define seed images
        $seedImages = collect([
            'seed-1.png',
            'seed-2.jpg',
            'seed-3.png',
        ]);
        // Reformat the url
        $seedImages = $seedImages->map(function ($seedImage) {
            return 'img/posts/' . $seedImage;
        });

        // Get all posts
        $posts = Post::select('id')->get();

        $posts->map(function ($post) use ($seedImages) {
            // Define how many images will be bind to the post
            $howManyImage = rand(0, $seedImages->count());

            $payload = [];
            for ($i=0; $i < $howManyImage; $i++) {
                $payload[] = [
                    'image' => $seedImages->random(),
                ];
            }

            if (count($payload) > 0) {
                $post->images()->createMany($payload);
            }
        });
    }
}
