<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '08123456789',
        ]);
        User::create([
            'name' => 'User Satu',
            'email' => 'user1@example.com',
            'password' => Hash::make('password'),
            'phone' => '08111111111',
        ]);
        User::create([
            'name' => 'User Dua',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
            'phone' => '08222222222',
        ]);
    }
}
