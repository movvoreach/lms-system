<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('semesters', function (Blueprint $table) {
            if (! Schema::hasColumn('semesters', 'study_year')) {
                $table->unsignedTinyInteger('study_year')->default(1)->after('academic_year_id');
            }

            if (! Schema::hasColumn('semesters', 'term_number')) {
                $table->unsignedTinyInteger('term_number')->default(1)->after('study_year');
            }
        });

        Schema::table('students', function (Blueprint $table) {
            if (! Schema::hasColumn('students', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable()->after('course_id');

                $table->foreign('department_id')
                    ->references('department_id')
                    ->on('departments')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            }
        });

        Schema::table('student_academic_year_records', function (Blueprint $table) {
            $table->index('student_id', 'sayr_student_idx');
            $table->index('academic_year_id', 'sayr_academic_year_idx');
            $table->dropUnique('student_year_unique');

            if (! Schema::hasColumn('student_academic_year_records', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable()->after('academic_year_id');

                $table->foreign('department_id')
                    ->references('department_id')
                    ->on('departments')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('student_academic_year_records', 'semester_id')) {
                $table->unsignedBigInteger('semester_id')->nullable()->after('department_id');

                $table->foreign('semester_id')
                    ->references('semester_id')
                    ->on('semesters')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('student_academic_year_records', 'study_year')) {
                $table->unsignedTinyInteger('study_year')->default(1)->after('semester_id');
            }

            if (! Schema::hasColumn('student_academic_year_records', 'term_number')) {
                $table->unsignedTinyInteger('term_number')->default(1)->after('study_year');
            }

            $table->unique(['student_id', 'academic_year_id', 'semester_id'], 'student_year_semester_unique');
        });

        Schema::table('student_course_registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('student_course_registrations', 'student_academic_year_record_id')) {
                $table->unsignedBigInteger('student_academic_year_record_id')->nullable()->after('academic_year_id');

                $table->foreign('student_academic_year_record_id', 'scr_record_fk')
                    ->references('record_id')
                    ->on('student_academic_year_records')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('student_course_registrations', 'semester_id')) {
                $table->unsignedBigInteger('semester_id')->nullable()->after('student_academic_year_record_id');

                $table->foreign('semester_id')
                    ->references('semester_id')
                    ->on('semesters')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('student_course_registrations', 'study_year')) {
                $table->unsignedTinyInteger('study_year')->default(1)->after('semester_id');
            }

            if (! Schema::hasColumn('student_course_registrations', 'term_number')) {
                $table->unsignedTinyInteger('term_number')->default(1)->after('study_year');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_course_registrations', function (Blueprint $table) {
            foreach (['term_number', 'study_year'] as $column) {
                if (Schema::hasColumn('student_course_registrations', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (Schema::hasColumn('student_course_registrations', 'semester_id')) {
                $table->dropForeign(['semester_id']);
                $table->dropColumn('semester_id');
            }

            if (Schema::hasColumn('student_course_registrations', 'student_academic_year_record_id')) {
                $table->dropForeign('scr_record_fk');
                $table->dropColumn('student_academic_year_record_id');
            }
        });

        Schema::table('student_academic_year_records', function (Blueprint $table) {
            $table->dropUnique('student_year_semester_unique');

            foreach (['term_number', 'study_year'] as $column) {
                if (Schema::hasColumn('student_academic_year_records', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (Schema::hasColumn('student_academic_year_records', 'semester_id')) {
                $table->dropForeign(['semester_id']);
                $table->dropColumn('semester_id');
            }

            if (Schema::hasColumn('student_academic_year_records', 'department_id')) {
                $table->dropForeign(['department_id']);
                $table->dropColumn('department_id');
            }

            $table->dropIndex('sayr_student_idx');
            $table->dropIndex('sayr_academic_year_idx');
            $table->unique(['student_id', 'academic_year_id'], 'student_year_unique');
        });

        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'department_id')) {
                $table->dropForeign(['department_id']);
                $table->dropColumn('department_id');
            }
        });

        Schema::table('semesters', function (Blueprint $table) {
            foreach (['term_number', 'study_year'] as $column) {
                if (Schema::hasColumn('semesters', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
