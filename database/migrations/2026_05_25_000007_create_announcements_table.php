<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->bigIncrements('announcement_id');
            $table->unsignedBigInteger('created_by_user_id');
            $table->foreignId('course_id')->nullable()->constrained('courses')->cascadeOnUpdate()->nullOnDelete();
            $table->string('title', 180);
            $table->longText('message');
            $table->string('attachment_path')->nullable();
            $table->string('priority', 20)->default('normal');
            $table->string('target_type', 30)->default('all');
            $table->string('status', 30)->default('draft');
            $table->timestamp('publish_at')->nullable();
            $table->timestamp('expire_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->foreign('created_by_user_id')
                ->references('user_id')
                ->on('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->index(['status', 'priority', 'target_type']);
            $table->index(['publish_at', 'expire_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
