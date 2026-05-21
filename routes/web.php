<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
});

require __DIR__ . '/academic/department.php';
require __DIR__ . '/academic/fuculty.php';

