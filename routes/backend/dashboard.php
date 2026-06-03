<?php

use App\Http\Controllers\AuthController;
use App\Models\ActivityLog;
use App\Models\CertificateRequest;
use App\Models\Course;
use App\Models\LearningIssueReport;
use App\Models\LessonContent;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Route;

Route::get('/admin/dashboard', function () {
    $studentTrendMonths = collect(range(5, 0))->map(function ($monthsAgo) {
        return now()->startOfMonth()->subMonths($monthsAgo);
    });

    return view('admin.dashboard', [
        'totalCourses' => Course::query()->count(),
        'activeCourses' => Course::query()->where('is_active', true)->count(),
        'totalStudents' => Student::query()->count(),
        'newStudentsToday' => Student::query()->whereDate('created_at', today())->count(),
        'totalTeachers' => Teacher::query()->count(),
        'totalLessons' => LessonContent::query()->count(),
        'pendingCertificateRequests' => CertificateRequest::query()->where('status', 'pending')->count(),
        'openLearningIssues' => LearningIssueReport::query()->whereIn('status', ['open', 'in_progress', 'waiting_student'])->count(),
        'urgentLearningIssues' => LearningIssueReport::query()->where('priority', 'urgent')->whereNotIn('status', ['resolved', 'closed'])->count(),
        'recentStudents' => Student::query()
            ->with(['user', 'course'])
            ->latest('student_id')
            ->limit(5)
            ->get(),
        'studentRegistrationLabels' => $studentTrendMonths
            ->map(fn ($month) => $month->format('M Y'))
            ->values(),
        'studentRegistrationTrend' => $studentTrendMonths
            ->map(fn ($month) => Student::query()
                ->whereBetween('created_at', [
                    $month->copy()->startOfMonth(),
                    $month->copy()->endOfMonth(),
                ])
                ->count())
            ->values(),
        'recentActivityLogs' => ActivityLog::query()
            ->with('user')
            ->latest('created_at')
            ->limit(6)
            ->get(),
        'todayActivityLogs' => ActivityLog::query()->whereDate('created_at', today())->count(),
    ]);
})->middleware('can:dashboard.access')->name('admin.dashboard');

Route::get('/profile', [AuthController::class, 'profile'])->name('profile.show');
Route::patch('/profile/avatar', [AuthController::class, 'updateAvatar'])->name('profile.avatar.update');
Route::patch('/profile/two-factor', [AuthController::class, 'updateTwoFactor'])->name('profile.two-factor.update');
