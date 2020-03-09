<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use App\User;
use Faker\Generator as Faker;

$users = User::all();
$userIds = $users->pluck('id');

$factory->define(Post::class, function (Faker $faker) use ($userIds) {
    return [
        'user_id' => $userIds->random(),
        'title' => $faker->sentence(rand(6, 12)),
        'body' => $faker->paragraph(rand(12, 20)),
    ];
});
