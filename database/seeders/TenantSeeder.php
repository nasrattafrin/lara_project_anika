<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $businessPlan = Plan::where('code', 'business')->first();
        $starterPlan = Plan::where('code', 'starter')->first();

        $tenants = [
            [
                'slug' => 'sagarika-press',
                'name' => 'Sagarika Printing Press',
                'email' => 'owner@abcpress.com',
                'phone' => '+8801711111111',
                'plan_id' => $businessPlan?->id,
                'status' => 'active',
                'trial_ends_at' => null,
                'settings' => ['timezone' => 'Asia/Dhaka', 'currency' => 'BDT'],
            ],
            [
                'slug' => 'tohura-print',
                'name' => 'Tohura Print House',
                'email' => 'owner@xyzprint.com',
                'phone' => '+8801722222222',
                'plan_id' => $starterPlan?->id,
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
                'settings' => ['timezone' => 'Asia/Dhaka', 'currency' => 'BDT'],
            ],
        ];

        foreach ($tenants as $tenant) {
            Tenant::updateOrCreate(
                ['slug' => $tenant['slug']],
                $tenant
            );
        }

        $this->command->info('2 demo tenants created (sagarika-press, tohura-print)!');
    }
}