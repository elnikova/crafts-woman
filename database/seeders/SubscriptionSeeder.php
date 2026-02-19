<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscriptions = [
            [
                'name' => 'Basic',
                'price' => 10,
                'features' => ['Unlimited listings', 'Basic analytics', 'Email support'],
            ],
            [
                'name' => 'Professional',
                'price' => 30,
                'features' => ['Unlimited listings', 'Advanced analytics', 'Priority support', 'Featured listings', 'Marketing tools'],
            ],
            [
                'name' => 'Enterprise',
                'price' => 100,
                'features' => ['Unlimited listings', 'Advanced analytics', 'Priority support', 'Featured listings', 'Marketing tools', 'API access', 'Dedicated account manager'],
            ],
        ];

        foreach ($subscriptions as $subscription) {
            if (!Subscription::query()->where('name', $subscription['name'])->exists()) {
                Subscription::create($subscription);
            }
        }
    }
}

