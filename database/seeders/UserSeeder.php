<?php

namespace Database\Seeders;

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
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Kurir
        User::create([
            'name' => 'Kurir User',
            'email' => 'kurir@gmail.com',
            'password' => Hash::make('kurir123'),
            'role' => 'kurir',
            'status' => 'active',
        ]);

        // Inactive user
        User::create([
            'name' => 'Nonaktif User',
            'email' => 'inactive@gmail.com',
            'password' => Hash::make('nonaktif123'),
            'role' => 'kurir',
            'status' => 'inactive',
        ]);
    }
}
