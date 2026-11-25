<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('order_number', 50)->unique();
            $table->enum('status', [
                'draft', 'quoted', 'confirmed', 'allocated',
                'in_production', 'qc', 'ready', 'delivered', 'cancelled',
            ])->default('draft');

            $table->decimal('total_amount', 12, 2)->default(0);
            $table->date('deadline')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index('deadline');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};