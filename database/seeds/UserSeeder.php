<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'adaw12',
            'email' => 'adaw@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);
    }
}
