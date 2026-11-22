<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PlanSeeder::class, 
            TenantSeeder::class, 
            RoleBaseSeeder::class,   // (roles + permissions)
            UserSeeder::class,       // (users + role assign)
        ]);
    }
}