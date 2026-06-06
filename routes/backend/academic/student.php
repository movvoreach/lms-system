<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/enrollment/courses', [StudentController::class, 'enrollmentCourses'])->name('students.enrollment.courses');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');

    Route::get('/student-enrollments', [StudentController::class, 'enrollmentIndex'])->name('student-enrollments.index');
    Route::get('/student-enrollments/{id}', [StudentController::class, 'enrollmentManage'])->name('student-enrollments.manage');
    Route::post('/student-enrollments/{id}', [StudentController::class, 'registerCourse'])->name('student-enrollments.store');
    Route::post('/student-enrollments/{id}/promote', [StudentController::class, 'promote'])->name('student-enrollments.promote');

    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::post('/students/{id}/courses', [StudentController::class, 'registerCourse'])->name('students.courses.store');
    Route::post('/students/{id}/promote', [StudentController::class, 'promote'])->name('students.promote');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
});
