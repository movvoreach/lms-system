<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('login');
})->name('website.home');

Route::get('/courses/{course}', fn () => redirect()->route('website.home'))
    ->name('website.courses.show');

Route::get('/learn/{slug}', fn () => redirect()->route('website.home'))
    ->name('website.static-course.show');

Route::get('/moodle-test', function () {
    $courses = collect([
        [
            'title' => 'Web Development Basics',
            'code' => 'WEB-101',
            'description' => 'Learn HTML, CSS, JavaScript, and the core skills needed to build modern websites.',
            'level' => 'Beginner',
            'duration' => '5 Modules',
            'progress' => 68,
            'teacher' => 'Sokha Chan',
            'modules' => [
                [
                    'title' => 'Topic 1: Getting started',
                    'summary' => 'Course orientation, web tools, and the first HTML page.',
                    'activities' => [
                        ['type' => 'Page', 'icon' => 'fa-file-alt', 'title' => 'Course introduction', 'status' => 'Done', 'meta' => 'Read'],
                        ['type' => 'Video', 'icon' => 'fa-play-circle', 'title' => 'Build your first web page', 'status' => 'Done', 'meta' => '12 min'],
                        ['type' => 'Forum', 'icon' => 'fa-comments', 'title' => 'Introduce yourself', 'status' => 'To do', 'meta' => '1 post required'],
                    ],
                ],
                [
                    'title' => 'Topic 2: HTML and CSS',
                    'summary' => 'Create structured pages and style them with clean CSS rules.',
                    'activities' => [
                        ['type' => 'Lesson', 'icon' => 'fa-book-open', 'title' => 'HTML page structure', 'status' => 'Done', 'meta' => '15 min'],
                        ['type' => 'File', 'icon' => 'fa-paperclip', 'title' => 'HTML cheat sheet', 'status' => 'Available', 'meta' => 'PDF'],
                        ['type' => 'Assignment', 'icon' => 'fa-clipboard-check', 'title' => 'Personal profile page', 'status' => 'Submit', 'meta' => '100 pts'],
                    ],
                ],
            ],
        ],
        [
            'title' => 'Laravel LMS Project',
            'code' => 'LMS-201',
            'description' => 'Build an LMS website with Laravel routes, controllers, Blade templates, and CRUD screens.',
            'level' => 'Intermediate',
            'duration' => '4 Modules',
            'progress' => 42,
            'teacher' => 'Dara Kim',
            'modules' => [
                [
                    'title' => 'Topic 1: Project setup',
                    'summary' => 'Install Laravel, configure routes, and prepare the application layout.',
                    'activities' => [
                        ['type' => 'Video', 'icon' => 'fa-play-circle', 'title' => 'Laravel project setup', 'status' => 'Done', 'meta' => '18 min'],
                        ['type' => 'Quiz', 'icon' => 'fa-question-circle', 'title' => 'Routing basics quiz', 'status' => 'To do', 'meta' => '10 questions'],
                        ['type' => 'URL', 'icon' => 'fa-link', 'title' => 'Laravel documentation', 'status' => 'Open', 'meta' => 'External link'],
                    ],
                ],
                [
                    'title' => 'Topic 2: Database and CRUD',
                    'summary' => 'Create models, migrations, controllers, and Blade forms.',
                    'activities' => [
                        ['type' => 'Lesson', 'icon' => 'fa-book-open', 'title' => 'Database and models', 'status' => 'In progress', 'meta' => '25 min'],
                        ['type' => 'Assignment', 'icon' => 'fa-clipboard-check', 'title' => 'Create course management', 'status' => 'Submit', 'meta' => 'Required'],
                        ['type' => 'File', 'icon' => 'fa-paperclip', 'title' => 'CRUD checklist', 'status' => 'Available', 'meta' => 'PDF'],
                    ],
                ],
            ],
        ],
        [
            'title' => 'UI Design Foundation',
            'code' => 'UI-110',
            'description' => 'Practice layout, spacing, color, and reusable components for clean LMS interfaces.',
            'level' => 'Beginner',
            'duration' => '3 Modules',
            'progress' => 25,
            'teacher' => 'Malis Heng',
            'modules' => [
                [
                    'title' => 'Topic 1: Visual foundations',
                    'summary' => 'Understand typography, spacing, color, and hierarchy.',
                    'activities' => [
                        ['type' => 'Lesson', 'icon' => 'fa-book-open', 'title' => 'Typography and spacing', 'status' => 'Done', 'meta' => '15 min'],
                        ['type' => 'Page', 'icon' => 'fa-file-alt', 'title' => 'Color systems', 'status' => 'To do', 'meta' => 'Read'],
                        ['type' => 'Quiz', 'icon' => 'fa-question-circle', 'title' => 'Design knowledge check', 'status' => 'To do', 'meta' => '10 min'],
                    ],
                ],
                [
                    'title' => 'Topic 2: LMS screens',
                    'summary' => 'Apply UI patterns to course, dashboard, and content pages.',
                    'activities' => [
                        ['type' => 'Forum', 'icon' => 'fa-comments', 'title' => 'Share dashboard design', 'status' => 'Required', 'meta' => 'Discussion'],
                        ['type' => 'Assignment', 'icon' => 'fa-clipboard-check', 'title' => 'Redesign a course page', 'status' => 'Submit', 'meta' => '100 pts'],
                    ],
                ],
            ],
        ],
    ]);

    $processSteps = [
        ['icon' => 'fa-user-plus', 'title' => 'Register', 'text' => 'Create a student account and choose a learning path.'],
        ['icon' => 'fa-book-open', 'title' => 'Study', 'text' => 'Open modules, watch videos, read lessons, and complete activities.'],
        ['icon' => 'fa-clipboard-check', 'title' => 'Assess', 'text' => 'Submit assignments and quizzes to measure progress.'],
        ['icon' => 'fa-award', 'title' => 'Complete', 'text' => 'Finish required lessons and request completion certificates.'],
    ];

    return view('frontend.layout.master', compact('courses', 'processSteps'));
})
    ->name('moodle.test');
