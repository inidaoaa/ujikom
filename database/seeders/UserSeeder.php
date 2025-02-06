<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
     User::create([
        'name'     => 'Sang Admin',
        'email'    => 'admin@gmail.com',
        'password' => bcrypt('password'),
     ]);

    }
}
