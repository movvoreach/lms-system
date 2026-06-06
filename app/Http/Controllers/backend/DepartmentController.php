<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests\DepartmentRequest\StoreDepartmentRequest;
use App\Http\Requests\DepartmentRequest\UpdateDepartmentRequest;
use App\Models\Faculty;
use App\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    protected DepartmentService $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    /**
     * =========================
     * LIST (VIEW PAGE)
     * =========================
     */
    public function index(Request $request)
    {
        $years = $this->departmentService->getDepartmentYears();
        $selectedYear = (int) ($request->integer('year') ?: ($years->first() ?: now()->year));
        $departments = $this->departmentService->getAllDepartments($selectedYear);

        return view('department.index', compact('departments', 'years', 'selectedYear'));
    }

    /**
     * =========================
     * CREATE FORM
     * =========================
     */
    public function create()
    {
        Faculty::query()->firstOrCreate(
            ['faculty_code' => 'GEN'],
            ['faculty_name' => 'General Faculty']
        );

        $faculties = Faculty::orderBy('faculty_name')->get();

        return view('department.create', compact('faculties'));
    }

    /**
     * =========================
     * STORE DATA
     * =========================
     */
    public function store(StoreDepartmentRequest $request)
    {
        $this->departmentService->createDepartment(
            $request->validated()
        );

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department created successfully');
    }

    /**
     * =========================
     * EDIT FORM
     * =========================
     */
    public function edit($id)
    {
        $department = $this->departmentService->findDepartmentById($id);

        Faculty::query()->firstOrCreate(
            ['faculty_code' => 'GEN'],
            ['faculty_name' => 'General Faculty']
        );

        $faculties = Faculty::orderBy('faculty_name')->get();

        return view('department.edit', compact('department', 'faculties'));
    }

    /**
     * =========================
     * UPDATE DATA
     * =========================
     */
    public function update(UpdateDepartmentRequest $request, $id)
    {
        $this->departmentService->updateDepartment(
            $id,
            $request->validated()
        );

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department updated successfully');
    }

    /**
     * =========================
     * DELETE DATA
     * =========================
     */
    public function destroy($id)
    {
        $this->departmentService->deleteDepartment($id);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Department deleted successfully');
    }

    /**
     * =========================
     * AJAX DATA (FOR LOADING + DATATABLE)
     * =========================
     */
    public function data()
    {
        $departments = $this->departmentService->getAllDepartments();

        return response()->json([
            'data' => $departments
        ]);
    }
}


