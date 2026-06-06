<?php

namespace App\Http\Controllers\backend;

use App\Models\Course;
use App\Models\LearningIssueReport;
use App\Models\LearningIssueReply;
use App\Models\LmsNotification;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LearningIssueReportController extends Controller
{
    public function index()
    {
        return view('learning-issue.index', [
            'issueTypes' => $this->issueTypes(),
            'statuses' => $this->statuses(),
            'priorities' => $this->priorities(),
        ]);
    }

    public function data(Request $request)
    {
        $reports = $this->scopedReports($request)
            ->with(['student.user', 'course', 'lessonContent', 'assignedTeacher.user'])
            ->when($request->filled('status'), fn (Builder $query) => $query->where('status', $request->status))
            ->when($request->filled('issue_type'), fn (Builder $query) => $query->where('issue_type', $request->issue_type))
            ->when($request->filled('priority'), fn (Builder $query) => $query->where('priority', $request->priority))
            ->latest('issue_report_id')
            ->get()
            ->map(fn (LearningIssueReport $report) => [
                'id' => $report->issue_report_id,
                'student' => e(($report->student->student_number ?? 'N/A') . ' - ' . ($report->student->user->username ?? 'N/A')),
                'course' => e($report->course->title ?? 'N/A'),
                'lesson' => e($report->lessonContent->title ?? 'General'),
                'title' => e($report->title),
                'type' => e($this->issueTypes()[$report->issue_type] ?? $report->issue_type),
                'priority' => view('learning-issue.partials.badge', ['type' => 'priority', 'value' => $report->priority])->render(),
                'status' => view('learning-issue.partials.badge', ['type' => 'status', 'value' => $report->status])->render(),
                'progress' => '<div class="progress progress-sm"><div class="progress-bar bg-info" style="width: '.$report->progress_percent.'%"></div></div><small>'.$report->progress_percent.'%</small>',
                'created_at' => $report->created_at?->format('Y-m-d H:i'),
                'action' => '<a class="btn btn-primary btn-sm" href="'.route('admin.learning-issues.show', $report->issue_report_id).'">View</a>',
            ]);

        return response()->json(['data' => $reports]);
    }

    public function create(Request $request)
    {
        $student = $request->user()->student;
        $courses = $student
            ? Course::query()->whereHas('studentRegistrations', fn ($query) => $query->where('student_id', $student->student_id))->orderBy('title')->get()
            : Course::query()->orderBy('title')->get();

        return view('learning-issue.create', [
            'courses' => $courses,
            'issueTypes' => $this->issueTypes(),
            'priorities' => $this->priorities(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => ['nullable', 'exists:courses,id'],
            'lesson_content_id' => ['nullable', 'exists:lesson_contents,id'],
            'title' => ['required', 'string', 'max:180'],
            'issue_type' => ['required', 'in:lesson_understanding,assignment_problem,missed_deadline,technical_problem,other'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'description' => ['required', 'string'],
            'deadline_at' => ['nullable', 'date'],
        ]);

        $student = $request->user()->student;
        abort_unless($student, 403, 'Only student accounts can submit learning issues.');

        $teacher = $this->teacherForCourse($validated['course_id'] ?? null);

        $report = LearningIssueReport::query()->create(array_merge($validated, [
            'student_id' => $student->student_id,
            'assigned_teacher_id' => $teacher?->teacher_id,
            'status' => 'open',
            'progress_percent' => 0,
        ]));

        if ($teacher?->user_id) {
            $this->notify($teacher->user_id, 'New learning issue', $report->title, route('admin.learning-issues.show', $report));
        }

        $this->notifyAdmins('New student issue', $report->title, route('admin.learning-issues.show', $report));

        return redirect()->route('admin.learning-issues.show', $report)->with('success', 'Issue submitted successfully.');
    }

    public function show(Request $request, int $learningIssue)
    {
        $report = $this->scopedReports($request)
            ->with(['student.user', 'course', 'lessonContent', 'assignedTeacher.user', 'replies.user'])
            ->findOrFail($learningIssue);

        return view('learning-issue.show', [
            'report' => $report,
            'statuses' => $this->statuses(),
        ]);
    }

    public function reply(Request $request, int $learningIssue)
    {
        $report = $this->scopedReports($request)->with(['student.user', 'assignedTeacher.user'])->findOrFail($learningIssue);

        $validated = $request->validate([
            'message' => ['required', 'string'],
            'status' => ['nullable', 'in:open,in_progress,waiting_student,resolved,closed'],
            'progress_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        DB::transaction(function () use ($request, $report, $validated): void {
            LearningIssueReply::query()->create([
                'issue_report_id' => $report->issue_report_id,
                'user_id' => $request->user()->user_id,
                'message' => $validated['message'],
                'is_teacher_feedback' => $request->user()->can('learning_issues.reply'),
            ]);

            $updates = [];
            if (isset($validated['status']) && $request->user()->can('learning_issues.reply')) {
                $updates['status'] = $validated['status'];
                $updates['resolved_at'] = $validated['status'] === 'resolved' ? now() : null;
            }
            if (isset($validated['progress_percent']) && $request->user()->can('learning_issues.reply')) {
                $updates['progress_percent'] = $validated['progress_percent'];
            }
            if ($updates) {
                $report->update($updates);
            }
        });

        $recipientId = $request->user()->user_id === $report->student->user_id
            ? $report->assignedTeacher?->user_id
            : $report->student->user_id;

        if ($recipientId) {
            $this->notify($recipientId, 'Learning issue reply', $report->title, route('admin.learning-issues.show', $report));
        }

        return back()->with('success', 'Reply saved.');
    }

    public function analytics(Request $request)
    {
        $query = $this->scopedReports($request);

        return view('learning-issue.analytics', [
            'total' => (clone $query)->count(),
            'open' => (clone $query)->whereIn('status', ['open', 'in_progress', 'waiting_student'])->count(),
            'resolved' => (clone $query)->where('status', 'resolved')->count(),
            'urgent' => (clone $query)->where('priority', 'urgent')->count(),
            'byType' => (clone $query)->selectRaw('issue_type, count(*) as total')->groupBy('issue_type')->pluck('total', 'issue_type'),
            'byStatus' => (clone $query)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'issueTypes' => $this->issueTypes(),
            'statuses' => $this->statuses(),
        ]);
    }

    private function scopedReports(Request $request): Builder
    {
        $user = $request->user();

        return LearningIssueReport::query()
            ->when($user->hasAnyRole(['Administrator', 'Admin', 'Manager']), fn ($query) => $query)
            ->when($user->hasRole('Student'), function ($query) use ($user) {
                $query->where('student_id', $user->student?->student_id ?? 0);
            })
            ->when($user->hasAnyRole(['Teacher', 'Non-editing Teacher']), function ($query) use ($user) {
                $teacher = $user->teacher;
                $courseIds = $teacher?->courseAssignments()->pluck('course_id')->all() ?? [];

                $query->where(function ($query) use ($teacher, $courseIds) {
                    $query->where('assigned_teacher_id', $teacher?->teacher_id ?? 0)
                        ->orWhereIn('course_id', $courseIds);
                });
            });
    }

    private function teacherForCourse(?int $courseId): ?Teacher
    {
        if (! $courseId) {
            return null;
        }

        return Teacher::query()
            ->whereHas('courseAssignments', fn ($query) => $query->where('course_id', $courseId))
            ->first();
    }

    private function notify(?int $userId, string $title, string $message, string $url): void
    {
        if (! $userId) {
            return;
        }

        LmsNotification::query()->create([
            'user_id' => $userId,
            'type' => 'learning_issue',
            'title' => $title,
            'message' => $message,
            'action_url' => $url,
        ]);
    }

    private function notifyAdmins(string $title, string $message, string $url): void
    {
        User::query()
            ->whereHas('roles', fn ($query) => $query->whereIn('role_name', ['Administrator', 'Admin', 'Manager']))
            ->pluck('user_id')
            ->each(fn ($userId) => $this->notify($userId, $title, $message, $url));
    }

    private function issueTypes(): array
    {
        return [
            'lesson_understanding' => 'Not understanding lesson',
            'assignment_problem' => 'Assignment problem',
            'missed_deadline' => 'Missing deadline',
            'technical_problem' => 'Technical problem',
            'other' => 'Other',
        ];
    }

    private function statuses(): array
    {
        return [
            'open' => 'Open',
            'in_progress' => 'In Progress',
            'waiting_student' => 'Waiting Student',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
        ];
    }

    private function priorities(): array
    {
        return [
            'low' => 'Low',
            'normal' => 'Normal',
            'high' => 'High',
            'urgent' => 'Urgent',
        ];
    }
}


