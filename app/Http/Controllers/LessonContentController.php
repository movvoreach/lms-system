<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonContentRequest\StoreLessonContentRequest;
use App\Http\Requests\LessonContentRequest\UpdateLessonContentRequest;
use App\Models\Course;
use App\Services\LessonContentService;
use Illuminate\Http\Request;
use Throwable;

class LessonContentController extends Controller
{
    public function __construct(
        protected LessonContentService $lessonContentService
    ) {
    }

    public function index()
    {
        $lessonContents = $this->lessonContentService->getAll();
        $pageTitle = 'មាតិកាមេរៀន';
        $pageDescription = 'គ្រប់គ្រងមេរៀន វីដេអូ ឯកសារ កិច្ចការ សំណួរ និងតំណភ្ជាប់ដូច Moodle។';
        $createRoute = route('admin.lesson-contents.create');

        return view('lesson-content.index', compact('lessonContents', 'pageTitle', 'pageDescription', 'createRoute'));
    }

    public function videos()
    {
        $lessonContents = $this->lessonContentService->getByType('video');
        $pageTitle = 'វីដេអូសិក្សា';
        $pageDescription = 'គ្រប់គ្រងវីដេអូមេរៀនតាមវគ្គសិក្សា។';
        $createRoute = route('admin.lesson-contents.create', ['type' => 'video']);

        return view('lesson-content.index', compact('lessonContents', 'pageTitle', 'pageDescription', 'createRoute'));
    }

    public function documents()
    {
        $lessonContents = $this->lessonContentService->getByType('file');
        $pageTitle = 'ឯកសារសិក្សា';
        $pageDescription = 'គ្រប់គ្រងឯកសារ និងឯកសារជំនួយការសិក្សាតាមវគ្គសិក្សា។';
        $createRoute = route('admin.lesson-contents.create', ['type' => 'file']);

        return view('lesson-content.index', compact('lessonContents', 'pageTitle', 'pageDescription', 'createRoute'));
    }

    public function create(Request $request)
    {
        $courses = Course::query()->orderBy('title')->get();
        $contentTypes = $this->contentTypes();
        $visibilityOptions = $this->visibilityOptions();
        $defaultContentType = array_key_exists($request->query('type'), $contentTypes)
            ? $request->query('type')
            : 'lesson';

        return view('lesson-content.create', compact('courses', 'contentTypes', 'visibilityOptions', 'defaultContentType'));
    }

    public function store(StoreLessonContentRequest $request)
    {
        try {
            $this->lessonContentService->store($request->validated());

            return redirect()->route('admin.lesson-contents.index')->with('success', 'បានបង្កើតមាតិកាមេរៀនដោយជោគជ័យ');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        $lessonContent = $this->lessonContentService->findById((int) $id);
        $courses = Course::query()->orderBy('title')->get();
        $contentTypes = $this->contentTypes();
        $visibilityOptions = $this->visibilityOptions();

        return view('lesson-content.edit', compact('lessonContent', 'courses', 'contentTypes', 'visibilityOptions'));
    }

    public function update(UpdateLessonContentRequest $request, $id)
    {
        try {
            $this->lessonContentService->update((int) $id, $request->validated());

            return redirect()->route('admin.lesson-contents.index')->with('success', 'បានកែប្រែមាតិកាមេរៀនដោយជោគជ័យ');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->lessonContentService->delete((int) $id);

            return redirect()->route('admin.lesson-contents.index')->with('success', 'បានលុបមាតិកាមេរៀនដោយជោគជ័យ');
        } catch (Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    protected function contentTypes(): array
    {
        return [
            'lesson' => 'មេរៀន',
            'page' => 'ទំព័រ',
            'video' => 'វីដេអូ',
            'file' => 'ឯកសារ',
            'url' => 'តំណភ្ជាប់',
            'assignment' => 'កិច្ចការ',
            'quiz' => 'សំណួរ',
            'forum' => 'វេទិកាពិភាក្សា',
        ];
    }

    protected function visibilityOptions(): array
    {
        return [
            'visible' => 'បង្ហាញ',
            'hidden' => 'លាក់',
            'scheduled' => 'កំណត់ពេល',
        ];
    }
}
