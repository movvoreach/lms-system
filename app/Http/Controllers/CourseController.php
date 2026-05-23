<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest\StoreCourseRequest;
use App\Http\Requests\CourseRequest\UpdateCourseRequest;
use App\Models\CourseCategory;
use App\Models\Department;
use App\Models\Semester;
use App\Services\CourseService;
use Throwable;

class CourseController extends Controller
{
    public function __construct(
        protected CourseService $courseService
    ) {
    }

    public function index()
    {
        $courses = $this->courseService->getAll();

        return view('course.index', compact('courses'));
    }

    public function create()
    {
        $courseCategories = CourseCategory::query()->orderBy('name')->get();
        $departments = Department::query()->orderBy('department_name')->get();
        $semesters = Semester::query()->with('academicYear')->orderByDesc('start_date')->get();

        return view('course.create', compact('courseCategories', 'departments', 'semesters'));
    }

    public function store(StoreCourseRequest $request)
    {
        try {
            $this->courseService->store($request->validated());

            return redirect()->route('admin.courses.index')->with('success', 'Course created successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        $course = $this->courseService->findById((int) $id);
        $courseCategories = CourseCategory::query()->orderBy('name')->get();
        $departments = Department::query()->orderBy('department_name')->get();
        $semesters = Semester::query()->with('academicYear')->orderByDesc('start_date')->get();

        return view('course.edit', compact('course', 'courseCategories', 'departments', 'semesters'));
    }

    public function update(UpdateCourseRequest $request, $id)
    {
        try {
            $this->courseService->update((int) $id, $request->validated());

            return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->courseService->delete((int) $id);

            return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully');
        } catch (Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
