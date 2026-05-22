<?php

namespace App\Services;

use App\Models\Semester;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class SemesterService
{
    public function getAll(): Collection
    {
        return Semester::query()
            ->with('academicYear')
            ->latest('semester_id')
            ->get();
    }

    public function store(array $data): Semester
    {
        try {
            return DB::transaction(fn () => Semester::create($data));
        } catch (Throwable $exception) {
            Log::error('Failed to create semester.', [
                'data' => $data,
                'error' => $exception->getMessage(),
            ]);

            throw new RuntimeException('Unable to create semester. Please try again.', 0, $exception);
        }
    }

    public function update(int $id, array $data): Semester
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $semester = $this->findById($id);
                $semester->update($data);

                return $semester;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to update semester.', [
                'semester_id' => $id,
                'data' => $data,
                'error' => $exception->getMessage(),
            ]);

            throw new RuntimeException('Unable to update semester. Please try again.', 0, $exception);
        }
    }

    public function delete(int $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                $semester = $this->findById($id);

                return (bool) $semester->delete();
            });
        } catch (Throwable $exception) {
            Log::error('Failed to delete semester.', [
                'semester_id' => $id,
                'error' => $exception->getMessage(),
            ]);

            throw new RuntimeException('Unable to delete semester. Please try again.', 0, $exception);
        }
    }

    public function findById(int $id): Semester
    {
        return Semester::query()->findOrFail($id);
    }
}
