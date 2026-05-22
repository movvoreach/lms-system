<?php

namespace App\Services;

use App\Models\CourseCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class CourseCategoryService
{
    public function getAll(): Collection
    {
        return CourseCategory::query()
            ->withCount('courses')
            ->latest('id')
            ->get();
    }

    public function store(array $data): CourseCategory
    {
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        try {
            return DB::transaction(fn () => CourseCategory::create($data));
        } catch (Throwable $exception) {
            Log::error('Failed to create course category.', ['data' => $data, 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to create course category. Please try again.', 0, $exception);
        }
    }

    public function update(int $id, array $data): CourseCategory
    {
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        try {
            return DB::transaction(function () use ($id, $data) {
                $category = $this->findById($id);
                $category->update($data);

                return $category;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to update course category.', ['id' => $id, 'data' => $data, 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to update course category. Please try again.', 0, $exception);
        }
    }

    public function delete(int $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                return (bool) $this->findById($id)->delete();
            });
        } catch (Throwable $exception) {
            Log::error('Failed to delete course category.', ['id' => $id, 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to delete course category. Remove related courses first.', 0, $exception);
        }
    }

    public function findById(int $id): CourseCategory
    {
        return CourseCategory::query()->findOrFail($id);
    }
}
