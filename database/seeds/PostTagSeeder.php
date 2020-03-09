<?php

use App\Post;
use App\Tag;
use Illuminate\Database\Seeder;

class PostTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all posts
        $posts = Post::all();

        // Get all tags
        $tags = Tag::select('id')->get()->pluck('id');
        // Get tags count
        $tagsCount = count($tags);

        $posts->map(function ($post) use ($tags, $tagsCount) {
            // Define how many tags will be bind to the post
            $howManyTags = rand(3, $tagsCount);

            $selectedTags = [];
            for ($i=0; $i < $howManyTags; $i++) {
                $selectedTags[] = $tags->random();
            }

            $post->tags()->sync($selectedTags);
        });
    }
}
