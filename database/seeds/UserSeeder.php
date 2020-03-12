<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userNames = collect([
            'subadrun',
            'alladin',
            'sasuke',
            'goku',
            'gon',
            'okarin',
            'misaka',
            'akame',
            'accelerator',
            'hajime'
        ]);

        $users = $userNames->map(function ($username) {
            $time_now = Carbon::now();
            return [
                'username' => $username,
                'email' => $username. "@example.com",
                'password' => '$2y$10$zBybZ74F/ZlkwaCXwczd4.TsjDwOQhDu4YzDr9/O0VKDFtQpUBhjq', // password
                'email_verified_at' => $time_now,
                'created_at' => $time_now,
                'updated_at' => $time_now,
            ];
        });

        DB::table('users')->insert($users->toArray());
    }
}
