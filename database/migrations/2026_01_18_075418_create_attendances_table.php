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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('marked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->integer('late_minutes')->nullable(); // Kechikish daqiqasi
            $table->integer('homework_score')->nullable();
            $table->integer('activity_score')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('marked_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->unique(['lesson_id', 'student_id', 'deleted_at']);
            $table->index(['student_id', 'lesson_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
