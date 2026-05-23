<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcademicYearProgressionRequest\PromoteStudentsRequest;
use App\Models\AcademicYear;
use App\Services\AcademicYearProgressionService;
use Throwable;

class AcademicYearProgressionController extends Controller
{
    public function __construct(
        protected AcademicYearProgressionService $progressionService
    ) {
    }

    public function index()
    {
        $academicYears = AcademicYear::query()
            ->withCount('studentRecords')
            ->orderByDesc('start_date')
            ->get();

        return view('academic-year.progression.index', compact('academicYears'));
    }

    public function show($id)
    {
        $academicYear = AcademicYear::query()->findOrFail($id);
        $records = $this->progressionService->recordsForYear((int) $id);

        return view('academic-year.progression.show', compact('academicYear', 'records'));
    }

    public function create()
    {
        $academicYears = AcademicYear::query()->orderByDesc('start_date')->get();
        $records = collect();

        if (request('from_academic_year_id')) {
            $records = $this->progressionService->recordsForYear((int) request('from_academic_year_id'));
        }

        return view('academic-year.progression.promote', compact('academicYears', 'records'));
    }

    public function store(PromoteStudentsRequest $request)
    {
        try {
            $count = $this->progressionService->promote($request->validated());

            return redirect()
                ->route('admin.academic-progression.index')
                ->with('success', "{$count} student record(s) promoted. Previous academic records were preserved.");
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }
}
