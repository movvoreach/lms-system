<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_lesson_grades', function (Blueprint $table) {
            $table->bigIncrements('grade_id');
            $table->unsignedBigInteger('student_id');
            $table->foreignId('course_id')->constrained('courses')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('lesson_content_id')->constrained('lesson_contents')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->decimal('score', 8, 2)->nullable();
            $table->decimal('max_score', 8, 2)->nullable();
            $table->boolean('passed')->default(false);
            $table->text('feedback')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'lesson_content_id']);

            $table->foreign('student_id')
                ->references('student_id')
                ->on('students')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('teacher_id')
                ->references('teacher_id')
                ->on('teachers')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_lesson_grades');
    }
};
