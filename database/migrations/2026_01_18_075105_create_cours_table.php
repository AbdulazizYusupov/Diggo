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
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code')->nullable(); // e.g., "ENG-BEG-01"
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2); // monthly price
            $table->integer('duration_months')->default(3);
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
