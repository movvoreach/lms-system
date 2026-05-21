<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;

Route::prefix('admin')->name('admin.')->group(function () {

    // =========================
    // DEPARTMENTS ROUTES
    // =========================

    Route::get('/departments', [DepartmentController::class, 'index'])
        ->name('departments.index');

    Route::get('/departments/create', [DepartmentController::class, 'create'])
        ->name('departments.create');

    Route::post('/departments', [DepartmentController::class, 'store'])
        ->name('departments.store');

    Route::get('/departments/{id}/edit', [DepartmentController::class, 'edit'])
        ->name('departments.edit');

    Route::put('/departments/{id}', [DepartmentController::class, 'update'])
        ->name('departments.update');

    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])
        ->name('departments.destroy');

    // =========================
    // AJAX DATA (FOR DATATABLE + LOADING)
    // =========================
    Route::get('/departments/data', [DepartmentController::class, 'data'])
        ->name('departments.data');

});
