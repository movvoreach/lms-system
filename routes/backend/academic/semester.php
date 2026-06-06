<?php

use App\Http\Controllers\backend\SemesterController;
use Illuminate\Support\Facades\Route;

Route::get('/semesters', [SemesterController::class, 'index'])
    ->name('semesters.index');

Route::get('/semesters/create', [SemesterController::class, 'create'])
    ->name('semesters.create');

Route::post('/semesters', [SemesterController::class, 'store'])
    ->name('semesters.store');

Route::get('/semesters/{id}/edit', [SemesterController::class, 'edit'])
    ->name('semesters.edit');

Route::put('/semesters/{id}', [SemesterController::class, 'update'])
    ->name('semesters.update');

Route::delete('/semesters/{id}', [SemesterController::class, 'destroy'])
    ->name('semesters.destroy');



