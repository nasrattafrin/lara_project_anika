<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allocation_scores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('allocation_id')->constrained()->cascadeOnDelete();

            $table->string('factor', 50);   // on_time, workload, cost, history, quality
            $table->decimal('weight', 5, 2);        // 30, 25, 20, 15, 10
            $table->decimal('raw_score', 5, 2);     // 0-100
            $table->decimal('weighted_score', 5, 2); // raw_score * weight / 100

            $table->timestamps();

            $table->index(['allocation_id', 'factor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allocation_scores');
    }
};