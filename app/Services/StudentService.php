<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentAcademicYearRecord;
use App\Models\StudentCourseRegistration;
use App\Models\Role;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class StudentService
{
    public function getAll(): Collection
    {
        return Student::query()
            ->with(['course', 'department', 'user', 'academicYearRecords.academicYear', 'academicYearRecords.semester'])
            ->latest('student_id')
            ->get();
    }

    public function store(array $data): Student
    {
        $enrollmentData = Arr::only($data, [
            'academic_year_id',
            'department_id',
            'semester_id',
            'study_year',
            'term_number',
            'course_ids',
        ]);

        $userData = Arr::only($data, ['username', 'email', 'password']);
        $studentData = Arr::except($data, [
            'username',
            'email',
            'password',
            'password_confirmation',
            'academic_year_id',
            'semester_id',
            'study_year',
            'term_number',
            'course_ids',
        ]);

        $studentData['course_id'] = $enrollmentData['course_ids'][0] ?? $studentData['course_id'] ?? null;

        try {
            return DB::transaction(function () use ($studentData, $userData, $enrollmentData) {
                $user = User::create(array_merge($userData, ['is_active' => true]));
                $this->assignRole($user, 'Student');

                $student = Student::create(array_merge($studentData, [
                    'user_id' => $user->user_id,
                ]));

                if (! blank($enrollmentData['academic_year_id'] ?? null)) {
                    $this->enrollAcademicPlacement($student, array_merge($enrollmentData, [
                        'status' => $student->status ?: 'enrolled',
                        'promotion_type' => 'initial',
                        'notes' => 'Initial academic enrollment',
                    ]));
                }

                return $student;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to create student.', ['data' => Arr::except($data, 'password'), 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to create student. Please try again.', 0, $exception);
        }
    }

    public function update(int $id, array $data): Student
    {
        unset($data['academic_year_id'], $data['semester_id'], $data['study_year'], $data['term_number'], $data['course_ids']);
        $userData = Arr::only($data, ['username', 'email']);

        if (filled($data['password'] ?? null)) {
            $userData['password'] = $data['password'];
        }

        $studentData = Arr::except($data, ['username', 'email', 'password', 'password_confirmation']);

        try {
            return DB::transaction(function () use ($id, $studentData, $userData) {
                $student = $this->findById($id);
                $student->user->update($userData);
                $student->update($studentData);

                return $student;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to update student.', ['student_id' => $id, 'data' => Arr::except($data, 'password'), 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to update student. Please try again.', 0, $exception);
        }
    }

    public function delete(int $id): bool
    {
        try {
            return DB::transaction(fn () => (bool) $this->findById($id)->delete());
        } catch (Throwable $exception) {
            Log::error('Failed to delete student.', ['student_id' => $id, 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to delete student. Please try again.', 0, $exception);
        }
    }

    public function findById(int $id): Student
    {
        return Student::query()
            ->with([
                'course',
                'department',
                'user',
                'academicYearRecords.academicYear',
                'academicYearRecords.department',
                'academicYearRecords.semester',
                'academicYearRecords.previousRecord.semester',
                'courseRegistrations.course.department',
                'courseRegistrations.course.semester.academicYear',
                'courseRegistrations.academicYear',
                'courseRegistrations.semester',
            ])
            ->findOrFail($id);
    }

    public function registerCourse(int $id, array $data): StudentCourseRegistration
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $student = $this->findById($id);

                if (blank($student->course_id)) {
                    $student->update(['course_id' => $data['course_id']]);
                }

                return StudentCourseRegistration::create([
                    'student_id' => $student->student_id,
                    'course_id' => $data['course_id'],
                    'academic_year_id' => $data['academic_year_id'] ?? null,
                    'semester_id' => $data['semester_id'] ?? null,
                    'study_year' => $data['study_year'] ?? 1,
                    'term_number' => $data['term_number'] ?? 1,
                    'status' => $data['status'] ?? 'registered',
                    'registered_at' => now(),
                    'notes' => $data['notes'] ?? null,
                ]);
            });
        } catch (Throwable $exception) {
            Log::error('Failed to register student course.', ['student_id' => $id, 'data' => $data, 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to register student to course. Please try again.', 0, $exception);
        }
    }

    public function enrollAcademicPlacement(Student $student, array $data): StudentAcademicYearRecord
    {
        $semester = Semester::query()->findOrFail($data['semester_id']);
        $courseIds = collect($data['course_ids'] ?? [])
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($courseIds->isEmpty()) {
            throw new RuntimeException('Please select at least one course for this enrollment.');
        }

        $validCourseIds = \App\Models\Course::query()
            ->where('department_id', $data['department_id'])
            ->where('semester_id', $semester->semester_id)
            ->whereIn('id', $courseIds)
            ->pluck('id');

        if ($validCourseIds->count() !== $courseIds->count()) {
            throw new RuntimeException('One or more selected courses do not match the selected department and semester.');
        }

        return DB::transaction(function () use ($student, $data, $semester, $validCourseIds) {
            $existingRecord = StudentAcademicYearRecord::query()
                ->where('student_id', $student->student_id)
                ->where('academic_year_id', $data['academic_year_id'])
                ->where('semester_id', $semester->semester_id)
                ->first();

            $record = StudentAcademicYearRecord::updateOrCreate(
                [
                    'student_id' => $student->student_id,
                    'academic_year_id' => $data['academic_year_id'],
                    'semester_id' => $semester->semester_id,
                ],
                [
                    'department_id' => $data['department_id'],
                    'study_year' => $data['study_year'] ?? $semester->study_year ?? 1,
                    'term_number' => $data['term_number'] ?? $semester->term_number ?? 1,
                    'course_id' => $validCourseIds->first(),
                    'status' => $data['status'] ?? 'enrolled',
                    'promotion_type' => $data['promotion_type'] ?? $existingRecord?->promotion_type ?? 'manual',
                    'promoted_from_record_id' => $data['promoted_from_record_id'] ?? $existingRecord?->promoted_from_record_id,
                    'promoted_at' => $data['promoted_at'] ?? $existingRecord?->promoted_at,
                    'notes' => $data['notes'] ?? null,
                ]
            );

            $student->update([
                'department_id' => $data['department_id'],
                'course_id' => $validCourseIds->first(),
                'status' => $data['status'] ?? $student->status ?? 'enrolled',
            ]);

            StudentCourseRegistration::query()
                ->where('student_id', $student->student_id)
                ->where('academic_year_id', $data['academic_year_id'])
                ->where('semester_id', $semester->semester_id)
                ->whereNotIn('course_id', $validCourseIds)
                ->delete();

            foreach ($validCourseIds as $courseId) {
                StudentCourseRegistration::updateOrCreate(
                    [
                        'student_id' => $student->student_id,
                        'course_id' => $courseId,
                        'academic_year_id' => $data['academic_year_id'],
                    ],
                    [
                        'student_academic_year_record_id' => $record->record_id,
                        'semester_id' => $semester->semester_id,
                        'study_year' => $record->study_year,
                        'term_number' => $record->term_number,
                        'status' => 'registered',
                        'registered_at' => now(),
                        'notes' => $data['notes'] ?? null,
                    ]
                );
            }

            return $record;
        });
    }

    private function assignRole(User $user, string $roleName): void
    {
        $roleId = Role::query()->where('role_name', $roleName)->value('role_id');

        if ($roleId) {
            $user->roles()->syncWithoutDetaching([$roleId]);
        }
    }
}
