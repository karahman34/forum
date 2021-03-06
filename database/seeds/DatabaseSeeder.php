<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);

        factory('App\Post', 100)->create();

        $this->call(TagSeeder::class);
        $this->call(PostTagSeeder::class);
        $this->call(PostSeenSeeder::class);
        $this->call(PostImageSeeder::class);
        $this->call(CommentSeeder::class);
        $this->call(CommentImageSeeder::class);
    }
}
