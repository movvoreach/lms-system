<?php

use App\Http\Controllers\LessonContentController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/lesson-videos', [LessonContentController::class, 'videos'])->name('lesson-videos.index');
    Route::get('/lesson-documents', [LessonContentController::class, 'documents'])->name('lesson-documents.index');
    Route::get('/lesson-contents', [LessonContentController::class, 'index'])->name('lesson-contents.index');
    Route::get('/lesson-contents/create', [LessonContentController::class, 'create'])->name('lesson-contents.create');
    Route::post('/lesson-contents', [LessonContentController::class, 'store'])->name('lesson-contents.store');
    Route::get('/lesson-contents/{id}/edit', [LessonContentController::class, 'edit'])->name('lesson-contents.edit');
    Route::put('/lesson-contents/{id}', [LessonContentController::class, 'update'])->name('lesson-contents.update');
    Route::delete('/lesson-contents/{id}', [LessonContentController::class, 'destroy'])->name('lesson-contents.destroy');
});
