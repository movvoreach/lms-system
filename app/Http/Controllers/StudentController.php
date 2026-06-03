<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest\StoreStudentRequest;
use App\Http\Requests\StudentRequest\UpdateStudentRequest;
use App\Models\Course;
use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentAcademicYearRecord;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class StudentController extends Controller
{
    public function __construct(
        protected StudentService $studentService
    ) {
    }

    public function index()
    {
        $students = $this->studentService->getAll();

        return view('student.index', compact('students'));
    }

    public function create()
    {
        return view('student.create', $this->formData());
    }

    public function store(StoreStudentRequest $request)
    {
        try {
            $this->studentService->store($request->validated());

            return redirect()->route('admin.students.index')->with('success', 'Student created successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        $student = $this->studentService->findById((int) $id);

        return view('student.edit', array_merge(['student' => $student], $this->formData()));
    }

    public function enrollmentIndex()
    {
        $students = Student::query()
            ->with(['user', 'department', 'academicYearRecords.academicYear', 'academicYearRecords.semester'])
            ->latest('student_id')
            ->get();

        return view('student-enrollment.index', compact('students'));
    }

    public function enrollmentManage($id)
    {
        $student = $this->studentService->findById((int) $id);

        return view('student-enrollment.manage', array_merge(['student' => $student], $this->formData()));
    }

    public function update(UpdateStudentRequest $request, $id)
    {
        try {
            $this->studentService->update((int) $id, $request->validated());

            return redirect()->route('admin.students.index')->with('success', 'Student updated successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->studentService->delete((int) $id);

            return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully');
        } catch (Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function registerCourse(Request $request, $id)
    {
        $validated = $request->validate([
            'department_id' => ['required', 'exists:departments,department_id'],
            'academic_year_id' => ['required', 'exists:academic_years,academic_year_id'],
            'semester_id' => ['required', 'exists:semesters,semester_id'],
            'study_year' => ['required', 'integer', 'min:1', 'max:4'],
            'term_number' => ['required', 'integer', 'in:1,2'],
            'course_ids' => ['required', 'array', 'min:1'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
            'status' => ['nullable', 'string', 'max:30'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            $student = $this->studentService->findById((int) $id);
            $this->studentService->enrollAcademicPlacement($student, array_merge($validated, [
                'promotion_type' => 'manual',
            ]));

            return back()->with('success', 'Student enrollment saved successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function enrollmentCourses(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'department_id' => ['required', 'exists:departments,department_id'],
            'academic_year_id' => ['required', 'exists:academic_years,academic_year_id'],
            'study_year' => ['required', 'integer', 'min:1', 'max:4'],
            'term_number' => ['required', 'integer', 'in:1,2'],
        ]);

        $semester = Semester::query()
            ->where('academic_year_id', $validated['academic_year_id'])
            ->where('study_year', $validated['study_year'])
            ->where('term_number', $validated['term_number'])
            ->first();

        $courses = collect();

        if ($semester) {
            $courses = Course::query()
                ->where('department_id', $validated['department_id'])
                ->where('semester_id', $semester->semester_id)
                ->where('is_active', true)
                ->orderBy('title')
                ->get(['id', 'title', 'code', 'duration_hours']);
        }

        return response()->json([
            'semester' => $semester ? [
                'id' => $semester->semester_id,
                'name' => $semester->semester_name,
            ] : null,
            'courses' => $courses,
        ]);
    }

    public function promote(Request $request, $id)
    {
        $validated = $request->validate([
            'record_id' => ['required', 'exists:student_academic_year_records,record_id'],
            'course_ids' => ['required', 'array', 'min:1'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            $student = $this->studentService->findById((int) $id);
            $current = StudentAcademicYearRecord::query()
                ->where('student_id', $student->student_id)
                ->findOrFail($validated['record_id']);

            $targetStudyYear = (int) $current->study_year;
            $targetTerm = (int) $current->term_number + 1;

            if ($targetTerm > 2) {
                $targetTerm = 1;
                $targetStudyYear++;
            }

            if ($targetStudyYear > 4) {
                return back()->with('error', 'This student has already completed the final study year.');
            }

            $targetSemester = Semester::query()
                ->where('academic_year_id', $current->academic_year_id)
                ->where('study_year', $targetStudyYear)
                ->where('term_number', $targetTerm)
                ->first();

            if (! $targetSemester) {
                return back()->with('error', 'Target semester was not found. Please create it first.');
            }

            DB::transaction(function () use ($student, $current, $targetSemester, $targetStudyYear, $targetTerm, $validated): void {
                $current->update([
                    'status' => 'completed',
                    'promoted_at' => $current->promoted_at ?? now(),
                ]);

                $this->studentService->enrollAcademicPlacement($student, [
                    'academic_year_id' => $current->academic_year_id,
                    'department_id' => $current->department_id,
                    'semester_id' => $targetSemester->semester_id,
                    'study_year' => $targetStudyYear,
                    'term_number' => $targetTerm,
                    'course_ids' => $validated['course_ids'],
                    'status' => 'promoted',
                    'promotion_type' => 'manual',
                    'promoted_from_record_id' => $current->record_id,
                    'promoted_at' => now(),
                    'notes' => $validated['notes'] ?? null,
                ]);
            });

            return back()->with('success', 'Student promoted and next semester courses registered successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    private function formData(): array
    {
        return [
            'courses' => Course::query()->with(['department', 'semester'])->orderBy('title')->get(),
            'departments' => Department::query()->orderBy('department_name')->get(),
            'academicYears' => AcademicYear::query()->orderByDesc('start_date')->get(),
        ];
    }
}
