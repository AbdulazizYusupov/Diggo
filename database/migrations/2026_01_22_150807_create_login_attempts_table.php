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
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();

            // Asosiy ustunlar
            $table->string('phone');
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();

            // Qurilma ma'lumotlari
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->string('device')->nullable();

            // Natija
            $table->boolean('successful')->default(false);
            $table->boolean('blocked')->default(false);
            $table->string('failure_reason')->nullable(); // wrong_password | user_not_found | user_inactive | rate_limited

            // Xavfsizlik
            $table->unsignedTinyInteger('risk_score')->default(0); // 0-100

            $table->timestamp('attempted_at')->useCurrent();
            $table->timestamps();

            // Tezroq query uchun indexlar
            $table->index('phone');
            $table->index('ip_address');
            $table->index('attempted_at');
            $table->index('risk_score');
            $table->index(['ip_address', 'attempted_at']);
            $table->index(['phone', 'attempted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_attempts');
    }
};
