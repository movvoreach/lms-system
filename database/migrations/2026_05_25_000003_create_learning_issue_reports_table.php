<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_issue_reports', function (Blueprint $table) {
            $table->bigIncrements('issue_report_id');
            $table->unsignedBigInteger('student_id');
            $table->foreignId('course_id')->nullable()->constrained('courses')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('lesson_content_id')->nullable()->constrained('lesson_contents')->cascadeOnUpdate()->nullOnDelete();
            $table->unsignedBigInteger('assigned_teacher_id')->nullable();
            $table->string('title', 180);
            $table->string('issue_type', 40)->default('lesson_understanding');
            $table->string('priority', 20)->default('normal');
            $table->string('status', 30)->default('open');
            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->text('description');
            $table->timestamp('deadline_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->foreign('student_id')
                ->references('student_id')
                ->on('students')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('assigned_teacher_id')
                ->references('teacher_id')
                ->on('teachers')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->index(['status', 'priority', 'issue_type']);
            $table->index(['course_id', 'assigned_teacher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_issue_reports');
    }
};
