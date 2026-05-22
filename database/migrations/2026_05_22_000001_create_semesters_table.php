<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('semesters', function (Blueprint $table) {
            $table->bigIncrements('semester_id');
            $table->unsignedBigInteger('academic_year_id');
            $table->string('semester_name', 50);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();

            $table->foreign('academic_year_id')
                ->references('academic_year_id')
                ->on('academic_years')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unique(['academic_year_id', 'semester_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};
