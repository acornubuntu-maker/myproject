<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create admin (only this credential allowed as admin)
        User::updateOrCreate(
            ['email' => 'admin@company.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Create some regular users (employees) under admin
        User::updateOrCreate(
            ['email' => 'jane@company.com'],
            [
                'name' => 'Jane Employee',
                'password' => Hash::make('emp123'),
                'role' => 'user',
            ]
        );

        User::factory()->count(3)->create(); // optional: create generated users
    }
}