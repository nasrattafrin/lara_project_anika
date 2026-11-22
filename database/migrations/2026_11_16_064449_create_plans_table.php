<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();

            // Basic info 
            $table->string('code', 50)->unique();   // starter, business, enterprise
            $table->string('name', 100);
            $table->text('description')->nullable();

            // Pricing
            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency', 3)->default('BDT');
            $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly');

            // Plan limits
            $table->json('limits')->nullable(); // {"units": 7, "users": 50, "storage_gb": 10}
            $table->json('features')->nullable(); // {"reports": true, "api_access": false}

            // Trial & status
            $table->integer('trial_days')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);     // display order

            // Production: soft delete
            $table->timestamps();
            $table->softDeletes();

            // Indexes (production performance)
            $table->index(['is_active', 'sort_order']);
            $table->index('billing_cycle');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};