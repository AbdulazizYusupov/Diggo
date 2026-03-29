<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('charge_logs', function (Blueprint $table) {
            $table->id();
            $table->date('charge_month')->unique();   // 2025-03-01
            $table->integer('charged_count')->default(0);
            $table->integer('skipped_count')->default(0);
            $table->integer('no_payment_count')->default(0);
            $table->integer('error_count')->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->json('details')->nullable();      // to'liq statistika
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamp('run_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charge_logs');
    }
};
