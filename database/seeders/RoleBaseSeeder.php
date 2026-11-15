<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleBaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cache reset
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Permissions list (document Page 2, Table 04 onujayi)
        $permissions = [
            // Tenant / Platform
            'tenants.view',
            'tenants.create',
            'tenants.update',
            'tenants.suspend',

            // Users
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            // Units
            'units.view',
            'units.create',
            'units.update',

            // Orders
            'orders.view',
            'orders.create',
            'orders.update',
            'orders.confirm',
            'orders.allocate',

            // Production
            'jobs.view',
            'jobs.update',
            'jobs.transfer',

            // Inventory
            'inventory.view',
            'inventory.create',
            'inventory.update',

            // Finance
            'invoices.view',
            'invoices.create',
            'payments.create',
            'expenses.view',

            // HR
            'employees.view',
            'employees.create',
            'payroll.view',
            'payroll.approve',

            // Reports
            'reports.unit',
            'reports.platform',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // 3. Roles + permission assign

        // Platform Admin
        Role::firstOrCreate(['name' => 'platform_admin', 'guard_name' => 'web'])
            ->syncPermissions([
                'tenants.view', 'tenants.create', 'tenants.update', 'tenants.suspend',
                'users.view', 'users.create', 'users.update', 'users.delete',
                'reports.platform',
            ]);

        // Press Responsible
        Role::firstOrCreate(['name' => 'press_responsible', 'guard_name' => 'web'])
            ->syncPermissions([
                'units.view', 'units.create', 'units.update',
                'orders.view', 'orders.create', 'orders.update', 'orders.confirm', 'orders.allocate',
                'jobs.view', 'jobs.update', 'jobs.transfer',
                'inventory.view',
                'invoices.view',
                'employees.view',
                'reports.unit',
            ]);

        // Unit Manager
        Role::firstOrCreate(['name' => 'unit_manager', 'guard_name' => 'web'])
            ->syncPermissions([
                'units.view',
                'orders.view',
                'jobs.view', 'jobs.update', 'jobs.transfer',
                'inventory.view',
                'employees.view',
                'reports.unit',
            ]);

        // Accountant
        Role::firstOrCreate(['name' => 'accountant', 'guard_name' => 'web'])
            ->syncPermissions([
                'orders.view',
                'invoices.view', 'invoices.create',
                'payments.create',
                'expenses.view',
                'payroll.view', 'payroll.approve',
                'reports.unit',
            ]);

        // Operator
        Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web'])
            ->syncPermissions([
                'jobs.view', 'jobs.update',
                'inventory.view',
            ]);

        // Customer
        Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web'])
            ->syncPermissions([
                'orders.view', 'orders.create',
                'invoices.view',
            ]);

        $this->command->info('✅ 6 roles + ' . count($permissions) . ' permissions created!');
    }
}