<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests\CourseCategoryRequest\StoreCourseCategoryRequest;
use App\Http\Requests\CourseCategoryRequest\UpdateCourseCategoryRequest;
use App\Services\CourseCategoryService;
use Throwable;

class CourseCategoryController extends Controller
{
    public function __construct(
        protected CourseCategoryService $courseCategoryService
    ) {
    }

    public function index()
    {
        $courseCategories = $this->courseCategoryService->getAll();

        return view('course-category.index', compact('courseCategories'));
    }

    public function create()
    {
        return view('course-category.create');
    }

    public function store(StoreCourseCategoryRequest $request)
    {
        try {
            $this->courseCategoryService->store($request->validated());

            return redirect()->route('admin.course-categories.index')->with('success', 'Course category created successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        $courseCategory = $this->courseCategoryService->findById((int) $id);

        return view('course-category.edit', compact('courseCategory'));
    }

    public function update(UpdateCourseCategoryRequest $request, $id)
    {
        try {
            $this->courseCategoryService->update((int) $id, $request->validated());

            return redirect()->route('admin.course-categories.index')->with('success', 'Course category updated successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->courseCategoryService->delete((int) $id);

            return redirect()->route('admin.course-categories.index')->with('success', 'Course category deleted successfully');
        } catch (Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}


