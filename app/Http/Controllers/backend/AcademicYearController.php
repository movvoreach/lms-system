<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests\AcademicYearRequest\StoreAcademicYearRequest;
use App\Http\Requests\AcademicYearRequest\UpdateAcademicYearRequest;
use App\Services\AcademicYearService;
use Throwable;

class AcademicYearController extends Controller
{
    public function __construct(
        protected AcademicYearService $academicYearService
    ) {
    }

    public function index()
    {
        $academicYears = $this->academicYearService->getAll();

        return view('academic-year.index', compact('academicYears'));
    }

    public function create()
    {
        return view('academic-year.create');
    }

    public function store(StoreAcademicYearRequest $request)
    {
        try {
            $this->academicYearService->store($request->validated());

            return redirect()
                ->route('admin.academic-years.index')
                ->with('success', 'Academic year created successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        $academicYear = $this->academicYearService->findById($id);

        return view('academic-year.edit', compact('academicYear'));
    }

    public function update(UpdateAcademicYearRequest $request, $id)
    {
        try {
            $this->academicYearService->update((int) $id, $request->validated());

            return redirect()
                ->route('admin.academic-years.index')
                ->with('success', 'Academic year updated successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->academicYearService->delete((int) $id);

            return redirect()
                ->route('admin.academic-years.index')
                ->with('success', 'Academic year deleted successfully');
        } catch (Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}


