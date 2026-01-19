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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('salary_type', [
                'fixed',        // oylik
                'hourly',       // soatbay
                'per_group',    // guruh bo‘yicha
                'percent'       // student yoki paymentdan %
            ])->default('fixed');
            $table->decimal('salary_amount', 15, 2)
                ->default(0);
            $table->decimal('percent_per_student', 5, 2)
                ->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
