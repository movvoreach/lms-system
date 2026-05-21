<?php

namespace App\Services;

use App\Models\Faculty;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class FacultyService
{
    public function getAll(): Collection
    {
        return Faculty::query()
            ->latest('faculty_id')
            ->get();
    }

    public function store(array $data): Faculty
    {
        try {
            return DB::transaction(fn () => Faculty::create($data));
        } catch (Throwable $exception) {
            Log::error('Failed to create faculty.', [
                'data' => $data,
                'error' => $exception->getMessage(),
            ]);

            throw new RuntimeException('មិនអាចបង្កើតមហាវិទ្យាល័យបានទេ។ សូមព្យាយាមម្ដងទៀត។', 0, $exception);
        }
    }

    public function update($id, array $data): Faculty
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $faculty = $this->findById($id);

                $faculty->update($data);

                return $faculty;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to update faculty.', [
                'faculty_id' => $id,
                'data' => $data,
                'error' => $exception->getMessage(),
            ]);

            throw new RuntimeException('មិនអាចកែប្រែមហាវិទ្យាល័យបានទេ។ សូមព្យាយាមម្ដងទៀត។', 0, $exception);
        }
    }

    public function delete($id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                $faculty = $this->findById($id);

                return (bool) $faculty->delete();
            });
        } catch (Throwable $exception) {
            Log::error('Failed to delete faculty.', [
                'faculty_id' => $id,
                'error' => $exception->getMessage(),
            ]);

            throw new RuntimeException('មិនអាចលុបមហាវិទ្យាល័យបានទេ។ សូមព្យាយាមម្ដងទៀត។', 0, $exception);
        }
    }

    public function findById($id): Faculty
    {
        return Faculty::query()->findOrFail($id);
    }
}

