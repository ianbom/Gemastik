<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Ian',
            'email' => 'ianbom@gmail.com',
            'password' => Hash::make('ianbom123'),
        ]);
        User::create([
            'name' => 'Argya',
            'email' => 'argya@gmail.com',
            'password' => Hash::make('argya123'),
        ]);
        User::create([
            'name' => 'User 1',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('user123'),
        ]);
        User::create([
            'name' => 'User 2',
            'email' => 'user2@gmail.com',
            'password' => Hash::make('user123'),
        ]);
        User::create([
            'name' => 'User 3',
            'email' => 'user3@gmail.com',
            'password' => Hash::make('user123'),
        ]);

        $komunitas = User::create([
            'name' => 'komunitas',
            'email' => 'komunitas@gmail.com',
            'password' => Hash::make('ianbom123'),
            'role' => 'community'

        ]);

        Community::create([
            'user_id' => $komunitas->id,
            'name' => $komunitas->name,
            'description' => 'Ini deskripsi komunitas',
            'member_count' => 10
        ]);
    }
}
