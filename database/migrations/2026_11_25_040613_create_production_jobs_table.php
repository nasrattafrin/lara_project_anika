<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_jobs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            $table->string('job_number', 50)->unique();
            $table->integer('quantity')->default(0);
            $table->enum('status', ['queued', 'printing', 'qc', 'ready', 'delivered'])->default('queued');
            $table->integer('rework_count')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->date('deadline')->nullable();

            $table->timestamps();

            $table->index(['unit_id', 'status']);
            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_jobs');
    }
};