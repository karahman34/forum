<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = collect([
            ['name' => 'anime'],
            ['name' => 'waifu'],
            ['name' => 'fight'],
            ['name' => 'world'],
            ['name' => 'refreshing'],
            ['name' => 'calm'],
            ['name' => 'santuy'],
            ['name' => 'meme'],
            ['name' => 'goree'],
            ['name' => 'isekai'],
            ['name' => 'fantasy'],
            ['name' => 'romantic'],
        ]);

        $tags = $tags->map(function ($tag) {
            return [
                'name' => $tag['name'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        });

        DB::table('tags')->insert($tags->toArray());
    }
}
