<?php

use App\Http\Controllers\backend\LearningIssueReportController;
use Illuminate\Support\Facades\Route;

Route::get('/learning-issues/data', [LearningIssueReportController::class, 'data'])
    ->middleware('can:learning_issues.view')
    ->name('learning-issues.data');

Route::get('/learning-issues/analytics', [LearningIssueReportController::class, 'analytics'])
    ->middleware('can:learning_issues.analytics')
    ->name('learning-issues.analytics');

Route::get('/learning-issues', [LearningIssueReportController::class, 'index'])
    ->middleware('can:learning_issues.view')
    ->name('learning-issues.index');

Route::get('/learning-issues/create', [LearningIssueReportController::class, 'create'])
    ->middleware('can:learning_issues.create')
    ->name('learning-issues.create');

Route::post('/learning-issues', [LearningIssueReportController::class, 'store'])
    ->middleware('can:learning_issues.create')
    ->name('learning-issues.store');

Route::get('/learning-issues/{learningIssue}', [LearningIssueReportController::class, 'show'])
    ->middleware('can:learning_issues.view')
    ->name('learning-issues.show');

Route::post('/learning-issues/{learningIssue}/replies', [LearningIssueReportController::class, 'reply'])
    ->middleware('can:learning_issues.view')
    ->name('learning-issues.replies.store');



