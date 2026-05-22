<?php

use App\Http\Controllers\AcademicYearController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/academic-years', [AcademicYearController::class, 'index'])
        ->name('academic-years.index');

    Route::get('/academic-years/create', [AcademicYearController::class, 'create'])
        ->name('academic-years.create');

    Route::post('/academic-years', [AcademicYearController::class, 'store'])
        ->name('academic-years.store');

    Route::get('/academic-years/{id}/edit', [AcademicYearController::class, 'edit'])
        ->name('academic-years.edit');

    Route::put('/academic-years/{id}', [AcademicYearController::class, 'update'])
        ->name('academic-years.update');

    Route::delete('/academic-years/{id}', [AcademicYearController::class, 'destroy'])
        ->name('academic-years.destroy');
});
