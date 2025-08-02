<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => "GUEST",
            'email' => 'guest@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'GUEST',
        ]);

        User::factory()->create([
            'name' => "STAFF",
            'email' => 'staff@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'STAFF',
        ]);

        User::factory()->create([
            'name' => "HEAD_STAFF",
            'email' => 'headstaff@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'HEAD_STAFF',
        ]);
    }
}
