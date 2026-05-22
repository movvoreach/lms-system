<?php

namespace App\Http\Controllers;

use App\Http\Requests\SemesterRequest\StoreSemesterRequest;
use App\Http\Requests\SemesterRequest\UpdateSemesterRequest;
use App\Models\AcademicYear;
use App\Services\SemesterService;
use Throwable;

class SemesterController extends Controller
{
    public function __construct(
        protected SemesterService $semesterService
    ) {
    }

    public function index()
    {
        $semesters = $this->semesterService->getAll();

        return view('semester.index', compact('semesters'));
    }

    public function create()
    {
        $academicYears = AcademicYear::query()
            ->orderByDesc('academic_year_id')
            ->get();

        return view('semester.create', compact('academicYears'));
    }

    public function store(StoreSemesterRequest $request)
    {
        try {
            $this->semesterService->store($request->validated());

            return redirect()
                ->route('admin.semesters.index')
                ->with('success', 'Semester created successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        $semester = $this->semesterService->findById((int) $id);
        $academicYears = AcademicYear::query()
            ->orderByDesc('academic_year_id')
            ->get();

        return view('semester.edit', compact('semester', 'academicYears'));
    }

    public function update(UpdateSemesterRequest $request, $id)
    {
        try {
            $this->semesterService->update((int) $id, $request->validated());

            return redirect()
                ->route('admin.semesters.index')
                ->with('success', 'Semester updated successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->semesterService->delete((int) $id);

            return redirect()
                ->route('admin.semesters.index')
                ->with('success', 'Semester deleted successfully');
        } catch (Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
