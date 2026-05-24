<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\Course;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/two-factor', [AuthController::class, 'showTwoFactor'])->name('two-factor.show');
    Route::post('/two-factor', [AuthController::class, 'verifyTwoFactor'])->name('two-factor.verify');
    Route::post('/two-factor/resend', [AuthController::class, 'resendTwoFactor'])->name('two-factor.resend');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/moodle-test', function () {
    $courses = Course::query()
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

    return view('static.moodle-test', compact('courses'));
})->name('moodle.test');

Route::middleware(['auth', 'two.factor'])->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/profile', [AuthController::class, 'profile'])->name('profile.show');
    Route::patch('/profile/two-factor', [AuthController::class, 'updateTwoFactor'])->name('profile.two-factor.update');

    require __DIR__ . '/academic/department.php';
    require __DIR__ . '/academic/fuculty.php';
    require __DIR__ . '/academic/academic_year.php';
    require __DIR__ . '/academic/academic_progression.php';
    require __DIR__ . '/academic/semester.php';
    require __DIR__ . '/academic/course_category.php';
    require __DIR__ . '/academic/course.php';
    require __DIR__ . '/academic/lesson_content.php';
    require __DIR__ . '/academic/user.php';
    require __DIR__ . '/academic/student.php';
    require __DIR__ . '/academic/teacher.php';
});
