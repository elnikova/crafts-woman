<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default admin user if it doesn't exist
        if (!User::query()->where('email', 'admin@craftwoman.local')->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@craftwoman.local',
                'password' => Hash::make('admin123'),
                'role' => Role::Admin,
                'gender' => 'female',
            ]);
        }
    }
}

