<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class DepartmentService
{
    /**
     * Get all departments (with relations)
     */
    public function getAllDepartments(): Collection
    {
        return Department::query()
            ->with('faculty')
            ->latest('department_id')
            ->get();
    }

    /**
     * Create new department
     */
    public function createDepartment(array $data): Department
    {
        try {
            return DB::transaction(function () use ($data) {
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
}
