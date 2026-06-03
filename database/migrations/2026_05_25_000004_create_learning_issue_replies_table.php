<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_issue_replies', function (Blueprint $table) {
            $table->bigIncrements('issue_reply_id');
            $table->unsignedBigInteger('issue_report_id');
            $table->unsignedBigInteger('user_id');
            $table->text('message');
            $table->boolean('is_teacher_feedback')->default(false);
            $table->timestamps();

            $table->foreign('issue_report_id')
                ->references('issue_report_id')
                ->on('learning_issue_reports')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_issue_replies');
    }
};
