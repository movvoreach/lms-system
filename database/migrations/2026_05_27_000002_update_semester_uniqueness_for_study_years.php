<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $semesters = DB::table('semesters')
            ->orderBy('academic_year_id')
            ->orderBy('semester_id')
            ->get()
            ->groupBy('academic_year_id');

        foreach ($semesters as $yearSemesters) {
            foreach ($yearSemesters->values() as $index => $semester) {
                $termNumber = ($index % 2) + 1;
                $studyYear = (int) floor($index / 2) + 1;

                DB::table('semesters')
                    ->where('semester_id', $semester->semester_id)
                    ->update([
                        'study_year' => $studyYear,
                        'term_number' => $termNumber,
                    ]);
            }
        }

        Schema::table('semesters', function (Blueprint $table) {
            if (! Schema::hasIndex('semesters', 'semesters_academic_year_idx')) {
                $table->index('academic_year_id', 'semesters_academic_year_idx');
            }

            if (Schema::hasIndex('semesters', 'semesters_academic_year_id_semester_name_unique')) {
                $table->dropUnique('semesters_academic_year_id_semester_name_unique');
            }

            $table->unique(['academic_year_id', 'study_year', 'term_number'], 'semesters_year_study_term_unique');
        });
    }

    public function down(): void
    {
        Schema::table('semesters', function (Blueprint $table) {
            $table->dropUnique('semesters_year_study_term_unique');
            $table->dropIndex('semesters_academic_year_idx');
            $table->unique(['academic_year_id', 'semester_name']);
        });
    }
};
