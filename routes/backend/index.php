<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'two.factor', 'activity.requests', 'telegram.action.alert'])->group(function () {
    require __DIR__ . '/profile.php';

    Route::prefix('admin')->name('admin.')->group(function () {
        require __DIR__ . '/dashboard.php';
        require __DIR__ . '/academic.php';
    });
});



