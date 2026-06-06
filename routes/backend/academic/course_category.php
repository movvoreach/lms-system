<?php

use App\Http\Controllers\backend\CourseCategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/course-categories', [CourseCategoryController::class, 'index'])->name('course-categories.index');
Route::get('/course-categories/create', [CourseCategoryController::class, 'create'])->name('course-categories.create');
Route::post('/course-categories', [CourseCategoryController::class, 'store'])->name('course-categories.store');
Route::get('/course-categories/{id}/edit', [CourseCategoryController::class, 'edit'])->name('course-categories.edit');
Route::put('/course-categories/{id}', [CourseCategoryController::class, 'update'])->name('course-categories.update');
Route::delete('/course-categories/{id}', [CourseCategoryController::class, 'destroy'])->name('course-categories.destroy');



