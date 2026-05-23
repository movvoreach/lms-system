<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherRequest\StoreTeacherRequest;
use App\Http\Requests\TeacherRequest\UpdateTeacherRequest;
use App\Models\Course;
use App\Services\TeacherService;
use Illuminate\Http\Request;
use Throwable;

class TeacherController extends Controller
{
    public function __construct(
        protected TeacherService $teacherService
    ) {
    }

    public function index()
    {
        $teachers = $this->teacherService->getAll();

        return view('teacher.index', compact('teachers'));
    }

    public function create()
    {
        return view('teacher.create', $this->formData());
    }

    public function store(StoreTeacherRequest $request)
    {
        try {
            $this->teacherService->store($request->validated());

            return redirect()->route('admin.teachers.index')->with('success', 'Teacher created successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        $teacher = $this->teacherService->findById((int) $id);

        return view('teacher.edit', array_merge(['teacher' => $teacher], $this->formData()));
    }

    public function update(UpdateTeacherRequest $request, $id)
    {
        try {
            $this->teacherService->update((int) $id, $request->validated());

            return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->teacherService->delete((int) $id);

            return redirect()->route('admin.teachers.index')->with('success', 'Teacher deleted successfully');
        } catch (Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function assignCourse(Request $request, $id)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'status' => ['nullable', 'string', 'max:30'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            $this->teacherService->assignCourse((int) $id, $validated);

            return back()->with('success', 'Teacher assigned to course successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    private function formData(): array
    {
        return [
            'courses' => Course::query()->orderBy('title')->get(),
        ];
    }
}
