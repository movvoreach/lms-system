<?php

namespace App\Services;

use App\Models\Teacher;
use App\Models\TeacherCourseAssignment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class TeacherService
{
    public function getAll(): Collection
    {
        return Teacher::query()
            ->with(['course', 'user'])
            ->latest('teacher_id')
            ->get();
    }

    public function store(array $data): Teacher
    {
        $userData = Arr::only($data, ['username', 'email', 'password']);
        $teacherData = Arr::except($data, ['username', 'email', 'password', 'password_confirmation']);

        try {
            return DB::transaction(function () use ($teacherData, $userData) {
                $user = User::create(array_merge($userData, ['is_active' => true]));
                $this->assignRole($user, 'Teacher');

                $teacher = Teacher::create(array_merge($teacherData, [
                    'user_id' => $user->user_id,
                ]));

                if ($teacher->course_id) {
                    TeacherCourseAssignment::create([
                        'teacher_id' => $teacher->teacher_id,
                        'course_id' => $teacher->course_id,
                        'status' => $teacher->status ?: 'assigned',
                        'assigned_at' => now(),
                        'notes' => 'Initial course assignment',
                    ]);
                }

                return $teacher;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to create teacher.', ['data' => Arr::except($data, 'password'), 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to create teacher. Please try again.', 0, $exception);
        }
    }

    public function update(int $id, array $data): Teacher
    {
        $userData = Arr::only($data, ['username', 'email']);

        if (filled($data['password'] ?? null)) {
            $userData['password'] = $data['password'];
        }

        $teacherData = Arr::except($data, ['username', 'email', 'password', 'password_confirmation']);

        try {
            return DB::transaction(function () use ($id, $teacherData, $userData) {
                $teacher = $this->findById($id);
                $teacher->user->update($userData);
                $teacher->update($teacherData);

                return $teacher;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to update teacher.', ['teacher_id' => $id, 'data' => Arr::except($data, 'password'), 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to update teacher. Please try again.', 0, $exception);
        }
    }

    public function delete(int $id): bool
    {
        try {
            return DB::transaction(fn () => (bool) $this->findById($id)->delete());
        } catch (Throwable $exception) {
            Log::error('Failed to delete teacher.', ['teacher_id' => $id, 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to delete teacher. Please try again.', 0, $exception);
        }
    }

    public function findById(int $id): Teacher
    {
        return Teacher::query()
            ->with(['course', 'user', 'courseAssignments.course.semester.academicYear'])
            ->findOrFail($id);
    }

    public function assignCourse(int $id, array $data): TeacherCourseAssignment
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $teacher = $this->findById($id);

                if (blank($teacher->course_id)) {
                    $teacher->update(['course_id' => $data['course_id']]);
                }

                return TeacherCourseAssignment::create([
                    'teacher_id' => $teacher->teacher_id,
                    'course_id' => $data['course_id'],
                    'status' => $data['status'] ?? 'assigned',
                    'assigned_at' => now(),
                    'notes' => $data['notes'] ?? null,
                ]);
            });
        } catch (Throwable $exception) {
            Log::error('Failed to assign teacher course.', ['teacher_id' => $id, 'data' => $data, 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to assign teacher to course. Please try again.', 0, $exception);
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
