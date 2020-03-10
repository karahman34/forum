<?php

use App\Comment;
use Illuminate\Database\Seeder;

class CommentImageSeeder extends Seeder
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
            return 'img/comments/' . $seedImage;
        });

        // Get all posts
        $comments = Comment::select('id')->get();

        $comments->map(function ($comment) use ($seedImages) {
            // Define how many images will be bind to comment
            $howManyImage = rand(0, $seedImages->count());

            $payload = [];
            for ($i=0; $i < $howManyImage; $i++) {
                $payload[] = [
                    'url' => $seedImages->random(),
                ];
            }

            if (count($payload) > 0) {
                $comment->images()->createMany($payload);
            }
        });
    }
}
