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
        Schema::create('shop_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // Kategoriya nomi
            $table->string('slug')->unique();                // URL slug
            $table->text('description')->nullable();         // Tavsif
            $table->string('icon')->nullable();              // Heroicon nomi yoki emoji
            $table->string('color', 20)->default('indigo'); // Rang (Tailwind color)
            $table->string('image')->nullable();             // Kategoriya rasmi
            $table->boolean('is_active')->default(true);    // Faolmi
            $table->integer('sort_order')->default(0);      // Tartib raqami
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_categories');
    }
};
