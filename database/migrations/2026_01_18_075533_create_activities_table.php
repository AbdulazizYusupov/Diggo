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
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('active')->index();
            $table->foreignId('group_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('file')->nullable();
            $table->date('date')->index();
            $table->time('start_time')->nullable();
            $table->time('due_time')->nullable();
            $table->integer('max_coins')->default(0);
            $table->boolean('is_required')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['group_id', 'type', 'date']);
            $table->index(['group_id', 'status']);
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
