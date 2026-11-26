<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $sagarika = Tenant::where('slug', 'sagarika-press')->first();
        $tohura   = Tenant::where('slug', 'tohura-print')->first();

        $users = [
            // platform admin er tenant nai
            ['name' => 'Platform Admin',    'email' => 'admin@printnexus.test',      'role' => 'platform_admin',    'tenant_id' => null],

            // Tenant 1: Sagarika
            ['name' => 'Press Responsible', 'email' => 'press@printnexus.test',      'role' => 'press_responsible', 'tenant_id' => $sagarika?->id],
            ['name' => 'Unit Manager',      'email' => 'manager@printnexus.test',    'role' => 'unit_manager',      'tenant_id' => $sagarika?->id],
            ['name' => 'Accountant',        'email' => 'accountant@printnexus.test', 'role' => 'accountant',        'tenant_id' => $sagarika?->id],
            ['name' => 'Operator',          'email' => 'operator@printnexus.test',   'role' => 'operator',          'tenant_id' => $sagarika?->id],
            ['name' => 'Customer',          'email' => 'customer@printnexus.test',   'role' => 'customer',          'tenant_id' => $sagarika?->id],

            // Tenant 2: Tohura (isolation test korar jonno)
            ['name' => 'Tohura Press',      'email' => 'press2@printnexus.test',     'role' => 'press_responsible', 'tenant_id' => $tohura?->id],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'      => $data['name'],
                    'password'  => Hash::make('password'),
                    'role'      => $data['role'],
                    'tenant_id' => $data['tenant_id'],
                    'status'    => User::STATUS_ACTIVE,
                ]
            );

            $user->syncRoles([$data['role']]);
        }

        $this->command->info('7 test users ready. Password: password');
    }
}