<?php

namespace App\Services;

use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class AcademicYearService
{
    public function getAll(): Collection
    {
        return AcademicYear::query()
            ->withCount('semesters')
            ->latest('academic_year_id')
            ->get();
    }

    public function store(array $data): AcademicYear
    {
        try {
            return DB::transaction(fn () => AcademicYear::create($data));
        } catch (Throwable $exception) {
            Log::error('Failed to create academic year.', [
                'data' => $data,
                'error' => $exception->getMessage(),
            ]);

            throw new RuntimeException('Unable to create academic year. Please try again.', 0, $exception);
        }
    }

    public function update(int $id, array $data): AcademicYear
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $academicYear = $this->findById($id);
                $academicYear->update($data);

                return $academicYear;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to update academic year.', [
                'academic_year_id' => $id,
                'data' => $data,
                'error' => $exception->getMessage(),
            ]);

            throw new RuntimeException('Unable to update academic year. Please try again.', 0, $exception);
        }
    }

    public function delete(int $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                $academicYear = $this->findById($id);

                return (bool) $academicYear->delete();
            });
        } catch (Throwable $exception) {
            Log::error('Failed to delete academic year.', [
                'academic_year_id' => $id,
                'error' => $exception->getMessage(),
            ]);

            throw new RuntimeException('Unable to delete academic year. Remove related semesters first.', 0, $exception);
        }
    }

    public function findById(int $id): AcademicYear
    {
        return AcademicYear::query()->findOrFail($id);
    }
}
