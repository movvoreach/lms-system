<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\AcademicBrowserController;
use App\Http\Controllers\backend\DepartmentController;

Route::get('/departments', [DepartmentController::class, 'index'])
    ->name('departments.index');

Route::get('/departments/create', [DepartmentController::class, 'create'])
    ->name('departments.create');

Route::get('/departments/{department}/courses', [AcademicBrowserController::class, 'departmentCourses'])
    ->name('departments.courses.index');

Route::post('/departments', [DepartmentController::class, 'store'])
    ->name('departments.store');

Route::get('/departments/{id}/edit', [DepartmentController::class, 'edit'])
    ->name('departments.edit');

Route::put('/departments/{id}', [DepartmentController::class, 'update'])
    ->name('departments.update');

Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])
    ->name('departments.destroy');

Route::get('/departments/data', [DepartmentController::class, 'data'])
    ->name('departments.data');



