<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\FacultyRequest\StoreFacultyRequest;
use App\Http\Requests\FacultyRequest\UpdateFacultyRequest;
use App\Services\FacultyService;
use Throwable;

class FacultyController extends Controller
{
    protected $facultyService;

    public function __construct(FacultyService $facultyService)
    {
        $this->facultyService = $facultyService;
    }

    public function index()
    {
        $faculties = $this->facultyService->getAll();
        return view('faculty.index', compact('faculties'));
    }

    public function create()
    {
        return view('faculty.create');
    }

    public function store(StoreFacultyRequest $request)
    {
        try {
            $this->facultyService->store($request->validated());

            return redirect()
                ->route('admin.faculty.index')
                ->with('success', 'បានបង្កើតមហាវិទ្យាល័យដោយជោគជ័យ។');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        $faculty = $this->facultyService->findById($id);
        return view('faculty.edit', compact('faculty'));
    }

    public function update(UpdateFacultyRequest $request, $id)
    {
        try {
            $this->facultyService->update($id, $request->validated());

            return redirect()
                ->route('admin.faculty.index')
                ->with('success', 'បានកែប្រែមហាវិទ្យាល័យដោយជោគជ័យ។');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->facultyService->delete($id);

            return redirect()
                ->route('admin.faculty.index')
                ->with('success', 'បានលុបមហាវិទ្យាល័យដោយជោគជ័យ។');
        } catch (Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
