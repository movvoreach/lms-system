<?php

use App\Http\Controllers\backend\AnnouncementController;
use App\Http\Controllers\backend\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/notifications/dropdown', [NotificationController::class, 'dropdown'])
    ->name('notifications.dropdown');
Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])
    ->name('notifications.read');

Route::get('/announcements/data', [AnnouncementController::class, 'data'])
    ->middleware('can:announcements.view')
    ->name('announcements.data');
Route::get('/announcements/{announcement}/notification', [AnnouncementController::class, 'showFromNotification'])
    ->middleware('can:announcements.view')
    ->name('announcements.notification.show');
Route::post('/announcements/{announcement}/publish', [AnnouncementController::class, 'publish'])
    ->middleware('can:announcements.manage')
    ->name('announcements.publish');
Route::post('/announcements/{announcement}/archive', [AnnouncementController::class, 'archive'])
    ->middleware('can:announcements.manage')
    ->name('announcements.archive');
Route::get('/announcements', [AnnouncementController::class, 'index'])
    ->middleware('can:announcements.view')
    ->name('announcements.index');
Route::get('/announcements/create', [AnnouncementController::class, 'create'])
    ->middleware('can:announcements.manage')
    ->name('announcements.create');
Route::post('/announcements', [AnnouncementController::class, 'store'])
    ->middleware('can:announcements.manage')
    ->name('announcements.store');
Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])
    ->middleware('can:announcements.view')
    ->name('announcements.show');
Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])
    ->middleware('can:announcements.manage')
    ->name('announcements.edit');
Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])
    ->middleware('can:announcements.manage')
    ->name('announcements.update');
Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])
    ->middleware('can:announcements.manage')
    ->name('announcements.destroy');



