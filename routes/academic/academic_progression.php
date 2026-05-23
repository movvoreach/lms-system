<?php

use App\Http\Controllers\AcademicYearProgressionController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/academic-progression', [AcademicYearProgressionController::class, 'index'])
        ->name('academic-progression.index');
    Route::get('/academic-progression/promote', [AcademicYearProgressionController::class, 'create'])
        ->name('academic-progression.promote');
    Route::post('/academic-progression/promote', [AcademicYearProgressionController::class, 'store'])
        ->name('academic-progression.store');
    Route::get('/academic-progression/{id}', [AcademicYearProgressionController::class, 'show'])
        ->name('academic-progression.show');
});
