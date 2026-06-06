<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\FacultyController;

Route::get('/faculty', [FacultyController::class, 'index'])
    ->name('faculty.index');

Route::get('/faculty/create', [FacultyController::class, 'create'])
    ->name('faculty.create');

Route::post('/faculty', [FacultyController::class, 'store'])
    ->name('faculty.store');

Route::get('/faculty/{id}/edit', [FacultyController::class, 'edit'])
    ->name('faculty.edit');

Route::put('/faculty/{id}', [FacultyController::class, 'update'])
    ->name('faculty.update');

Route::delete('/faculty/{id}', [FacultyController::class, 'destroy'])
    ->name('faculty.destroy');

Route::get('/faculty/data', [FacultyController::class, 'data'])
    ->name('faculty.data');



