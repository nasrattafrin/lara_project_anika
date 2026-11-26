<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('process_type', 30);
            $table->unsignedInteger('capacity_per_day');
            $table->decimal('cost_per_unit', 10, 4)->default(0);
            $table->enum('status', ['active', 'maintenance', 'inactive'])->default('active');
            $table->timestamps();

            $table->index(['unit_id', 'status']);
            $table->index(['tenant_id', 'process_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};