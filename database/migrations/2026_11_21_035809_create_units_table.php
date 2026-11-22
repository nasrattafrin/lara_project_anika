<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();

            // Tenant (doc: Page 6, Table 14)
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();

            // Basic
            $table->string('name', 100);
            $table->text('description')->nullable();

            // Status
            $table->enum('status', ['active', 'inactive', 'maintenance'])
                  ->default('active');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['tenant_id', 'status']);
            $table->unique(['tenant_id', 'name']);   // ek tenant e same name nei
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};