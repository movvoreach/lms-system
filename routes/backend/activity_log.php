<?php

use App\Http\Controllers\backend\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::middleware('can:activity_logs.view')->group(function () {
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/data', [ActivityLogController::class, 'data'])->name('activity-logs.data');
    Route::get('/activity-logs/export/excel', [ActivityLogController::class, 'exportExcel'])->name('activity-logs.export.excel');
    Route::get('/activity-logs/export/pdf', [ActivityLogController::class, 'exportPdf'])->name('activity-logs.export.pdf');
});



