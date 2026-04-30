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
        Schema::create('payment_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->string('action'); // 'created', 'updated', 'cancelled'
            $table->json('old_values')->nullable();
            $table->json('new_values');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();

            $table->index(['payment_id', 'created_at']);
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_audits');
    }
};
