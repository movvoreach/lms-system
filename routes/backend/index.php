<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'two.factor', 'activity.requests'])->group(function () {
    require __DIR__ . '/dashboard.php';
    require __DIR__ . '/academic.php';
});
