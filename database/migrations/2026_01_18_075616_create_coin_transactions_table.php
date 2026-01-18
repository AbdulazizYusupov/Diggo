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
        Schema::create('coin_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('activity_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('coins');
            $table->enum('type', [
                'participation',
                'homework',
                'exam',
                'competition',
                'active_involvement',
                'penalty',
                'bonus',
                'purchase',
                'other'
            ])->index();
            $table->text('description')->nullable();
            $table->integer('balance_after')->index();
            $table->date('transaction_date')->index();
            $table->date('expiry_date')->nullable()->index();
            $table->timestamps();
            $table->index(['student_id', 'transaction_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coin_transactions');
    }
};
