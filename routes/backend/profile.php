<?php

use App\Http\Controllers\backend\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/profile', [AuthController::class, 'profile'])->name('profile.show');
Route::patch('/profile/avatar', [AuthController::class, 'updateAvatar'])->name('profile.avatar.update');
Route::patch('/profile/two-factor', [AuthController::class, 'updateTwoFactor'])->name('profile.two-factor.update');



