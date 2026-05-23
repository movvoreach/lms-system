<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class CourseService
{
    public function getAll(): Collection
    {
        return Course::query()
            ->with(['category', 'department', 'semester.academicYear'])
            ->latest('id')
            ->get();
    }

    public function store(array $data): Course
    {
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        try {
            return DB::transaction(fn () => Course::create($data));
        } catch (Throwable $exception) {
            Log::error('Failed to create course.', ['data' => $data, 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to create course. Please try again.', 0, $exception);
        }
    }

    public function update(int $id, array $data): Course
    {
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        try {
            return DB::transaction(function () use ($id, $data) {
                $course = $this->findById($id);
                $course->update($data);

                return $course;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to update course.', ['id' => $id, 'data' => $data, 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to update course. Please try again.', 0, $exception);
        }
    }

    public function delete(int $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                return (bool) $this->findById($id)->delete();
            });
        } catch (Throwable $exception) {
            Log::error('Failed to delete course.', ['id' => $id, 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to delete course. Please try again.', 0, $exception);
        }
    }

    public function findById(int $id): Course
    {
        return Course::query()->findOrFail($id);
    }
}
