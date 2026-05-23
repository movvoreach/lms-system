<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentAcademicYearRecord;
use App\Models\StudentCourseRegistration;
use App\Models\Role;
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
            ->with(['course', 'user'])
            ->latest('student_id')
            ->get();
    }

    public function store(array $data): Student
    {
        $academicYearId = $data['academic_year_id'] ?? null;
        unset($data['academic_year_id']);

        $userData = Arr::only($data, ['username', 'email', 'password']);
        $studentData = Arr::except($data, ['username', 'email', 'password', 'password_confirmation']);

        try {
            return DB::transaction(function () use ($studentData, $userData, $academicYearId) {
                $user = User::create(array_merge($userData, ['is_active' => true]));
                $this->assignRole($user, 'Student');

                $student = Student::create(array_merge($studentData, [
                    'user_id' => $user->user_id,
                ]));

                if ($academicYearId) {
                    StudentAcademicYearRecord::create([
                        'student_id' => $student->student_id,
                        'academic_year_id' => $academicYearId,
                        'course_id' => $student->course_id,
                        'status' => $student->status ?: 'enrolled',
                        'promotion_type' => 'initial',
                    ]);
                }

                if ($student->course_id) {
                    StudentCourseRegistration::create([
                        'student_id' => $student->student_id,
                        'course_id' => $student->course_id,
                        'academic_year_id' => $academicYearId,
                        'status' => $student->status ?: 'registered',
                        'registered_at' => now(),
                        'notes' => 'Initial course registration',
                    ]);
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
        unset($data['academic_year_id']);
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
            ->with(['course', 'user', 'courseRegistrations.course.semester.academicYear', 'courseRegistrations.academicYear'])
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

    private function assignRole(User $user, string $roleName): void
    {
        $roleId = Role::query()->where('role_name', $roleName)->value('role_id');

        if ($roleId) {
            $user->roles()->syncWithoutDetaching([$roleId]);
        }
    }
}
