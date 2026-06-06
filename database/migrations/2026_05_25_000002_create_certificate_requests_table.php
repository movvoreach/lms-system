<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_requests', function (Blueprint $table) {
            $table->bigIncrements('certificate_request_id');
            $table->unsignedBigInteger('student_id');
            $table->foreignId('course_id')->constrained('courses')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedBigInteger('registration_id')->nullable();
            $table->unsignedBigInteger('requested_by_teacher_id')->nullable();
            $table->unsignedBigInteger('reviewed_by_user_id')->nullable();
            $table->string('status', 30)->default('pending');
            $table->text('teacher_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'course_id', 'status']);

            $table->foreign('student_id')
                ->references('student_id')
                ->on('students')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('registration_id')
                ->references('registration_id')
                ->on('student_course_registrations')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign('requested_by_teacher_id')
                ->references('teacher_id')
                ->on('teachers')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign('reviewed_by_user_id')
                ->references('user_id')
                ->on('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_requests');
    }
};
