<?php

namespace App\Services;

use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class DepartmentService
{
    /**
     * Get all departments (with relations)
     */
    public function getAllDepartments(?int $year = null): Collection
    {
        return Department::query()
            ->with('faculty')
            ->withCount('courses')
            ->when($year, function ($query) use ($year) {
                $query->whereYear('created_at', $year);
            })
            ->latest('department_id')
            ->get();
    }

    public function getDepartmentYears(): SupportCollection
    {
        return Department::query()
            ->selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');
    }

    /**
     * Create new department
     */
    public function createDepartment(array $data): Department
    {
        try {
            return DB::transaction(function () use ($data) {
                $data['faculty_id'] = $data['faculty_id'] ?? $this->defaultFacultyId();

                return Department::create($data);
            });
        } catch (Throwable $exception) {

            Log::error('Department creation failed', [
                'data' => $data,
                'error' => $exception->getMessage(),
            ]);

            throw new RuntimeException(
                'មិនអាចបង្កើតដេប៉ាតឺម៉ង់បានទេ។ សូមព្យាយាមម្ដងទៀត។',
                0,
                $exception
            );
        }
    }

    /**
     * Update department
     */
    public function updateDepartment(int $id, array $data): Department
    {
        try {
            return DB::transaction(function () use ($id, $data) {

                $department = $this->findDepartmentById($id);
                $data['faculty_id'] = $data['faculty_id'] ?? $this->defaultFacultyId();

                $department->update($data);

                return $department;
            });

        } catch (Throwable $exception) {

            Log::error('Department update failed', [
                'department_id' => $id,
                'data' => $data,
                'error' => $exception->getMessage(),
            ]);

            throw new RuntimeException(
                'មិនអាចកែប្រែដេប៉ាតឺម៉ង់បានទេ។ សូមព្យាយាមម្ដងទៀត។',
                0,
                $exception
            );
        }
    }

    /**
     * Delete department
     */
    public function deleteDepartment(int $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {

                $department = $this->findDepartmentById($id);

                return (bool) $department->delete();
            });

        } catch (Throwable $exception) {

            Log::error('Department deletion failed', [
                'department_id' => $id,
                'error' => $exception->getMessage(),
            ]);

            throw new RuntimeException(
                'មិនអាចលុបដេប៉ាតឺម៉ង់បានទេ។ សូមព្យាយាមម្ដងទៀត។',
                0,
                $exception
            );
        }
    }

    /**
     * Find department by ID
     */
    public function findDepartmentById(int $id): Department
    {
        return Department::query()->findOrFail($id);
    }

    private function defaultFacultyId(): int
    {
        return Faculty::query()->firstOrCreate(
            ['faculty_code' => 'GEN'],
            ['faculty_name' => 'General Faculty']
        )->faculty_id;
    }
}
