<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::middleware(['auth', 'two.factor'])->group(function () {
Route::get('/', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

require __DIR__ . '/academic/department.php';
require __DIR__ . '/academic/fuculty.php';
require __DIR__ . '/academic/academic_year.php';
require __DIR__ . '/academic/semester.php';
require __DIR__ . '/academic/course_category.php';
require __DIR__ . '/academic/course.php';
require __DIR__ . '/academic/user.php';
});
