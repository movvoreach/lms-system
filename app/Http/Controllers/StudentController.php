<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest\StoreStudentRequest;
use App\Http\Requests\StudentRequest\UpdateStudentRequest;
use App\Models\Course;
use App\Models\AcademicYear;
use App\Services\StudentService;
use Illuminate\Http\Request;
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
            'course_id' => ['required', 'exists:courses,id'],
            'academic_year_id' => ['nullable', 'exists:academic_years,academic_year_id'],
            'status' => ['nullable', 'string', 'max:30'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            $this->studentService->registerCourse((int) $id, $validated);

            return back()->with('success', 'Student registered to course successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    private function formData(): array
    {
        return [
            'courses' => Course::query()->orderBy('title')->get(),
            'academicYears' => AcademicYear::query()->orderByDesc('start_date')->get(),
        ];
    }
}
