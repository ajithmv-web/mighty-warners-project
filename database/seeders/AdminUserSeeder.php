<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('Admin1@112'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'user@gmail.com',
            'password' => bcrypt('User2@112'),
            'role' => 'user',
        ]);
    }
}
