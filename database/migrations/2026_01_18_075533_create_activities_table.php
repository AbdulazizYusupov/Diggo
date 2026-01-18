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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->enum('type', ['homework', 'exam', 'competition', 'project', 'other'])->index();
            $table->foreignId('group_id')->nullable()->constrained()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->date('date')->index();
            $table->integer('max_coins')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['group_id', 'type', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
