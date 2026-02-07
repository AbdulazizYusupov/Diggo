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
        // Schema::create('payments', function (Blueprint $table) {
        //     $table->id();

        //     $table->foreignId('student_id')->constrained()->cascadeOnDelete();
        //     $table->foreignId('user_id')->constrained(); // Kassir
        //     $table->foreignId('director_id')->nullable()->constrained()->nullOnDelete();

        //     $table->decimal('amount', 15, 2);
        //     $table->decimal('discount', 15, 2)->default(0);
        //     $table->decimal('net_amount', 15, 2);
        //     $table->decimal('director_commission', 15, 2)->default(0);

        //     $table->enum('payment_type', ['cash', 'card', 'transfer', 'other'])->default('cash');
        //     $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('paid')->index();

        //     $table->date('payment_month')->index();
        //     $table->timestamp('paid_at')->nullable()->index();
        //     $table->boolean('is_installment')->default(false);
        //     $table->integer('installment_number')->nullable();
        //     $table->integer('total_installments')->nullable();

        //     $table->text('notes')->nullable();

        //     $table->timestamps();
        //     $table->softDeletes();

        //     $table->index(['student_id', 'payment_month']);
        // });
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->morphs('payable'); // Student, Group
            $table->foreignId('group_id')->constrained();
            $table->foreignId('user_id')->constrained(); // Kassir
            $table->decimal('amount', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2);
            $table->enum('payment_type', ['cash', 'card', 'transfer', 'other'])->default('cash');
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('paid');
            $table->date('payment_month')->index();
            $table->timestamp('paid_at')->nullable();
            $table->boolean('is_installment')->default(false);
            $table->integer('installment_number')->nullable();
            $table->integer('total_installments')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
