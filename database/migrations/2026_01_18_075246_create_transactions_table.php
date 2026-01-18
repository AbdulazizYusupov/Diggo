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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['deposit', 'withdraw', 'refund', 'adjustment'])->index();
            $table->string('description')->nullable();
            $table->morphs('transactionable');
            $table->decimal('balance_after', 15, 2);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('transaction_date')->index();
            $table->timestamps();
            $table->index(['student_id', 'transaction_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
