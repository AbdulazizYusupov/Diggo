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
        Schema::create('user_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('action', ['login', 'logout']);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device')->nullable();       // Mobile / Desktop / Tablet
            $table->string('browser')->nullable();      // Chrome, Firefox...
            $table->string('platform')->nullable();     // Windows, Mac, Android...
            $table->timestamp('logged_at');
            $table->timestamps();

            $table->index(['user_id', 'action']);
            $table->index('logged_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activity_logs');
    }
};
