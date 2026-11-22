<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'code' => 'starter',
                'name' => 'Starter',
                'description' => 'Perfect for small printing presses getting started.',
                'price' => 5000.00,
                'currency' => 'BDT',
                'billing_cycle' => 'monthly',
                'limits' => [
                    'units' => 3,
                    'users' => 20,
                    'storage_gb' => 5,
                    'orders_per_month' => 100,
                ],
                'features' => [
                    'reports' => true,
                    'api_access' => false,
                    'custom_domain' => false,
                    'priority_support' => false,
                ],
                'trial_days' => 14,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'code' => 'business',
                'name' => 'Business',
                'description' => 'Full-featured plan for growing presses with 7 units.',
                'price' => 15000.00,
                'currency' => 'BDT',
                'billing_cycle' => 'monthly',
                'limits' => [
                    'units' => 7,
                    'users' => 50,
                    'storage_gb' => 25,
                    'orders_per_month' => 1000,
                ],
                'features' => [
                    'reports' => true,
                    'api_access' => true,
                    'custom_domain' => true,
                    'priority_support' => false,
                ],
                'trial_days' => 14,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'code' => 'enterprise',
                'name' => 'Enterprise',
                'description' => 'Unlimited scale for large printing groups.',
                'price' => 50000.00,
                'currency' => 'BDT',
                'billing_cycle' => 'monthly',
                'limits' => [
                    'units' => 20,
                    'users' => 200,
                    'storage_gb' => 100,
                    'orders_per_month' => 10000,
                ],
                'features' => [
                    'reports' => true,
                    'api_access' => true,
                    'custom_domain' => true,
                    'priority_support' => true,
                ],
                'trial_days' => 30,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['code' => $plan['code']],
                $plan
            );
        }

        $this->command->info(' 3 plans created (starter, business, enterprise)!');
    }
}