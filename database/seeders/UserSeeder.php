<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Platform Admin',    'email' => 'admin@printnexus.test',      'role' => 'platform_admin'],
            ['name' => 'Press Responsible', 'email' => 'press@printnexus.test',      'role' => 'press_responsible'],
            ['name' => 'Unit Manager',      'email' => 'manager@printnexus.test',    'role' => 'unit_manager'],
            ['name' => 'Accountant',        'email' => 'accountant@printnexus.test', 'role' => 'accountant'],
            ['name' => 'Operator',          'email' => 'operator@printnexus.test',   'role' => 'operator'],
            ['name' => 'Customer',          'email' => 'customer@printnexus.test',   'role' => 'customer'],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => Hash::make('password'),
                    'status'   => User::STATUS_ACTIVE,
                ]
            );

            $user->syncRoles([$data['role']]);
        }

        $this->command->info(' 6 test users created with Spatie roles!');
        $this->command->info('Password (sob role er): password');
    }
}