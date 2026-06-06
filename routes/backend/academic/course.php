<?php

use App\Http\Controllers\backend\AcademicBrowserController;
use App\Http\Controllers\backend\CourseController;
use Illuminate\Support\Facades\Route;

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
Route::get('/courses/{course}/students', [AcademicBrowserController::class, 'courseStudents'])->name('courses.students.index');
Route::get('/courses/{course}/students/{student}', [AcademicBrowserController::class, 'studentDetail'])->name('courses.students.show');
Route::get('/courses/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
Route::put('/courses/{id}', [CourseController::class, 'update'])->name('courses.update');
Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');



