<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
           
            if (!Schema::hasColumn('users', 'tenant_id')) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', [
                    'platform_admin',
                    'press_responsible',
                    'unit_manager',
                    'accountant',
                    'operator',
                    'customer',
                ])->default('customer')->after('password');
            }

            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'suspended'])->default('active')->after('role');
            }

            if (!Schema::hasColumn('users', 'unit_id')) {
                $table->unsignedBigInteger('unit_id')->nullable()->after('status');
            }
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tenant_id', 'role', 'status', 'unit_id']);
        });
    }
};