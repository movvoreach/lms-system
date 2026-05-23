<?php

namespace App\Services;

use App\Models\StudentAcademicYearRecord;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class AcademicYearProgressionService
{
    public function recordsForYear(int $academicYearId): Collection
    {
        return StudentAcademicYearRecord::query()
            ->with(['student.user', 'academicYear', 'course', 'previousRecord.academicYear'])
            ->where('academic_year_id', $academicYearId)
            ->orderBy('student_id')
            ->get();
    }

    public function promote(array $data): int
    {
        $studentIds = $data['student_ids'] ?? [];
        $fromYearId = (int) $data['from_academic_year_id'];
        $toYearId = (int) $data['to_academic_year_id'];
        $targetStatus = $data['target_status'] ?? 'promoted';
        $promotionType = $data['promotion_type'] ?? 'manual';
        $notes = $data['notes'] ?? null;

        try {
            return DB::transaction(function () use ($studentIds, $fromYearId, $toYearId, $targetStatus, $promotionType, $notes) {
                $records = StudentAcademicYearRecord::query()
                    ->where('academic_year_id', $fromYearId)
                    ->when($studentIds, fn ($query) => $query->whereIn('student_id', $studentIds))
                    ->whereNotIn('student_id', function ($query) use ($toYearId) {
                        $query->select('student_id')
                            ->from('student_academic_year_records')
                            ->where('academic_year_id', $toYearId);
                    })
                    ->get();

                foreach ($records as $record) {
                    StudentAcademicYearRecord::create([
                        'student_id' => $record->student_id,
                        'academic_year_id' => $toYearId,
                        'course_id' => $record->course_id,
                        'status' => $targetStatus,
                        'promotion_type' => $promotionType,
                        'promoted_from_record_id' => $record->record_id,
                        'promoted_at' => now(),
                        'notes' => $notes,
                    ]);
                }

                return $records->count();
            });
        } catch (Throwable $exception) {
            Log::error('Failed to promote students.', ['data' => $data, 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to promote students. Please try again.', 0, $exception);
        }
    }
}
