<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleBaseSeeder::class,   // (roles + permissions)
            UserSeeder::class,       // (users + role assign)
        ]);
    }
}