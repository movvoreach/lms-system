<?php

use App\Http\Controllers\backend\CertificateRequestController;
use App\Http\Controllers\backend\CourseGradeController;
use Illuminate\Support\Facades\Route;

Route::get('/course-grades', [CourseGradeController::class, 'index'])
    ->middleware('can:grades.manage')
    ->name('course-grades.index');

Route::put('/course-grades', [CourseGradeController::class, 'bulkUpdate'])
    ->middleware('can:grades.manage')
    ->name('course-grades.bulk-update');

Route::post('/course-grades/certificate-requests', [CourseGradeController::class, 'requestCourseCertificates'])
    ->middleware('can:certificates.request')
    ->name('course-grades.course-certificate-requests');

Route::get('/course-grades/{registration}', [CourseGradeController::class, 'edit'])
    ->middleware('can:grades.manage')
    ->name('course-grades.edit');

Route::put('/course-grades/{registration}', [CourseGradeController::class, 'update'])
    ->middleware('can:grades.manage')
    ->name('course-grades.update');

Route::post('/course-grades/{registration}/certificate-request', [CourseGradeController::class, 'requestCertificate'])
    ->middleware('can:certificates.request')
    ->name('course-grades.certificate-request');

Route::get('/certificate-requests', [CertificateRequestController::class, 'index'])
    ->middleware('can:certificates.manage')
    ->name('certificate-requests.index');

Route::put('/certificate-requests/{certificateRequest}', [CertificateRequestController::class, 'update'])
    ->middleware('can:certificates.manage')
    ->name('certificate-requests.update');



