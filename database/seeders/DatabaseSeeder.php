<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed subscriptions first (required by users)
        $this->call(SubscriptionSeeder::class);

        // Seed default admin user
        $this->call(AdminUserSeeder::class);

        // Create test users
        User::factory(10)->create();
    }
}
