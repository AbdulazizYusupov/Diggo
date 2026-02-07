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
        // Schema::create('transactions', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('student_id')->constrained()->cascadeOnDelete();
        //     $table->decimal('amount', 15, 2);
        //     $table->enum('type', ['deposit', 'withdraw', 'refund', 'adjustment','commission'])->index();
        //     $table->string('description')->nullable();
        //     $table->morphs('transactionable');
        //     $table->decimal('balance_after', 15, 2);
        //     $table->foreignId('created_by')->constrained('users');
        //     $table->timestamp('transaction_date')->index();
        //     $table->timestamps();
        //     $table->index(['student_id', 'transaction_date']);
        // });
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Kimga tegishli? (Student, Director, Teacher)
            $table->morphs('transactionable');

            $table->decimal('amount', 15, 2);
            $table->decimal('balance_before', 15, 2)->default(0);
            $table->decimal('balance_after', 15, 2);

            $table->enum('type', [
                'payment',           // O'quvchi to'lovi
                'charge',            // Dars hisobi
                'refund',            // Qaytarim
                'director_commission', // Direktor komissiyasi
                'teacher_commission',  // O'qituvchi komissiyasi
                'adjustment',        // Tuzatish
                'withdraw',          // Yechib olish
                'deposit'            // Qo'shish
            ])->index();

            // Qaysi manbadan? (Payment, LessonCharge, etc.)
            $table->morphs('source');

            // Komissiya uchun qo'shimcha ma'lumotlar
            $table->decimal('percentage', 5, 2)->nullable()->comment('Komissiya foizi');
            $table->decimal('base_amount', 15, 2)->nullable()->comment('Asos summa');

            $table->enum('status', ['pending', 'approved', 'paid', 'cancelled'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            // $table->timestamp('transaction_date')->index();
            $table->timestamp('transaction_date')->useCurrent()->index();

            $table->timestamps();
            $table->softDeletes();
            $table->index(['type', 'status']);
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
