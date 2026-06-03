<?php

use App\Models\Course;
use Illuminate\Support\Facades\Route;

$staticCourses = function () {
    return collect([
        [
            'slug' => 'c-programming',
            'title' => 'ភាសា C',
            'code' => 'C',
            'category' => 'គន្លឹះកូដ',
            'style' => 'default',
            'icon' => 'fas fa-code',
            'duration' => 18,
            'description' => 'រៀនមូលដ្ឋាន C programming ពី syntax, variables, conditions, loops និង functions សម្រាប់អ្នកចាប់ផ្តើម។',
            'modules' => [
                [
                    'number' => 1,
                    'title' => 'Introduction to C Programming',
                    'lessons' => [
                        ['position' => 1, 'slug' => 'overview', 'title' => 'Overview of C Programming'],
                        ['position' => 2, 'slug' => 'history', 'title' => 'History of C'],
                        ['position' => 3, 'slug' => 'features', 'title' => 'Features of C'],
                        ['position' => 4, 'slug' => 'environment', 'title' => 'Setting Up the C Environment'],
                    ],
                ],
                [
                    'number' => 2,
                    'title' => 'Variables and Data Types',
                    'lessons' => [
                        ['position' => 1, 'slug' => 'variables', 'title' => 'Declaring Variables'],
                        ['position' => 2, 'slug' => 'data-types', 'title' => 'Basic Data Types'],
                        ['position' => 3, 'slug' => 'constants', 'title' => 'Constants'],
                    ],
                ],
            ],
        ],
        [
            'slug' => 'python-basics',
            'title' => 'ភាសា Python',
            'code' => 'Python',
            'category' => 'គន្លឹះកូដ',
            'style' => 'python',
            'icon' => 'fab fa-python',
            'duration' => 24,
            'description' => 'រៀន Python ពីមូលដ្ឋានដល់ការបង្កើត program តូចៗ ដោយមានឧទាហរណ៍ងាយយល់ និងលំហាត់អនុវត្ត។',
            'modules' => [
                [
                    'number' => 1,
                    'title' => 'Python Foundation',
                    'lessons' => [
                        ['position' => 1, 'slug' => 'intro', 'title' => 'Introduction to Python'],
                        ['position' => 2, 'slug' => 'syntax', 'title' => 'Python Syntax'],
                        ['position' => 3, 'slug' => 'input-output', 'title' => 'Input and Output'],
                    ],
                ],
                [
                    'number' => 2,
                    'title' => 'Control Flow',
                    'lessons' => [
                        ['position' => 1, 'slug' => 'conditions', 'title' => 'If Else Conditions'],
                        ['position' => 2, 'slug' => 'loops', 'title' => 'Loops'],
                    ],
                ],
            ],
        ],
        [
            'slug' => 'git-version-control',
            'title' => 'ភាសា Git',
            'code' => 'git',
            'category' => 'ឧបករណ៍អ្នកអភិវឌ្ឍ',
            'style' => 'git',
            'icon' => 'fab fa-git-alt',
            'duration' => 10,
            'description' => 'រៀនប្រើ Git សម្រាប់គ្រប់គ្រង source code, commit, branch, merge និងធ្វើការជាក្រុម។',
            'modules' => [
                [
                    'number' => 1,
                    'title' => 'Git Essentials',
                    'lessons' => [
                        ['position' => 1, 'slug' => 'what-is-git', 'title' => 'What is Git?'],
                        ['position' => 2, 'slug' => 'commit', 'title' => 'Commit and History'],
                        ['position' => 3, 'slug' => 'branch', 'title' => 'Branch and Merge'],
                    ],
                ],
            ],
        ],
    ]);
};

Route::get('/', function () {
    $courses = Course::query()
        ->with('category')
        ->withCount('lessonContents')
        ->where('is_active', true)
        ->whereHas('lessonContents', function ($query) {
            $query->where('is_published', true)
                ->where('visibility', 'visible')
                ->where(function ($query) {
                    $query->whereNull('available_from')->orWhere('available_from', '<=', now());
                })
                ->where(function ($query) {
                    $query->whereNull('available_until')->orWhere('available_until', '>=', now());
                });
        })
        ->with(['lessonContents' => function ($query) {
            $query->where('is_published', true)
                ->where('visibility', 'visible')
                ->where(function ($query) {
                    $query->whereNull('available_from')->orWhere('available_from', '<=', now());
                })
                ->where(function ($query) {
                    $query->whereNull('available_until')->orWhere('available_until', '>=', now());
                })
                ->orderBy('module_number')
                ->orderBy('position');
        }])
        ->orderBy('title')
        ->get();

    $categories = $courses
        ->pluck('category')
        ->filter()
        ->unique('id')
        ->values();

    $staticCourses = app('website.static_courses')();

    return view('website.courses.index', compact('courses', 'categories', 'staticCourses'));
})->name('website.home');

Route::get('/courses/{course}', function (Course $course) {
    $course->load([
        'category',
        'lessonContents' => function ($query) {
            $query->where('is_published', true)
                ->where('visibility', 'visible')
                ->where(function ($query) {
                    $query->whereNull('available_from')->orWhere('available_from', '<=', now());
                })
                ->where(function ($query) {
                    $query->whereNull('available_until')->orWhere('available_until', '>=', now());
                })
                ->orderBy('module_number')
                ->orderBy('position');
        },
    ]);

    abort_if(! $course->is_active, 404);

    $lessons = $course->lessonContents;
    $modules = $lessons->groupBy('module_number');
    $selectedLesson = $lessons->firstWhere('slug', request('lesson')) ?? $lessons->first();
    $relatedCourses = Course::query()
        ->where('is_active', true)
        ->whereKeyNot($course->id)
        ->orderBy('title')
        ->limit(10)
        ->get();

    return view('website.courses.show', compact('course', 'lessons', 'modules', 'selectedLesson', 'relatedCourses'));
})->name('website.courses.show');

Route::get('/learn/{slug}', function (string $slug) {
    $staticCourses = app('website.static_courses')();
    $course = $staticCourses->firstWhere('slug', $slug);

    abort_if(! $course, 404);

    $modules = collect($course['modules']);
    $lessons = $modules->flatMap(function ($module) {
        return collect($module['lessons'])->map(function ($lesson) use ($module) {
            return array_merge($lesson, [
                'module_number' => $module['number'],
                'module_title' => $module['title'],
            ]);
        });
    })->values();
    $selectedLesson = $lessons->firstWhere('slug', request('lesson')) ?? $lessons->first();
    $relatedCourses = $staticCourses->where('slug', '!=', $slug)->values();

    return view('website.courses.static-show', compact('course', 'modules', 'lessons', 'selectedLesson', 'relatedCourses'));
})->name('website.static-course.show');

Route::get('/moodle-test', function () {
    return redirect()->route('website.home');
})->name('moodle.test');

app()->instance('website.static_courses', $staticCourses);
