@extends('admin.layouts.master')

@section('title', 'Learning Issue Detail')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1">{{ $report->title }}</h1>
                    <p class="text-muted mb-0">{{ $report->course->title ?? 'General' }} / {{ $report->lessonContent->title ?? 'No lesson selected' }}</p>
                </div>
                <a href="{{ route('admin.learning-issues.index') }}" class="btn btn-light border">Back</a>
            </div>
        </div>
    </section>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Report</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        @include('learning-issue.partials.badge', ['type' => 'priority', 'value' => $report->priority])
                        @include('learning-issue.partials.badge', ['type' => 'status', 'value' => $report->status])
                    </div>
                    <p>{{ $report->description }}</p>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-info" style="width: {{ $report->progress_percent }}%"></div>
                    </div>
                    <small class="text-muted">Progress: {{ $report->progress_percent }}%</small>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Conversation</h3>
                </div>
                <div class="card-body">
                    @forelse ($report->replies as $reply)
                        <div class="border rounded p-3 mb-3 {{ $reply->is_teacher_feedback ? 'bg-light' : '' }}">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $reply->user->username ?? 'User' }}</strong>
                                <small class="text-muted">{{ $reply->created_at->format('Y-m-d H:i') }}</small>
                            </div>
                            <p class="mb-0 mt-2">{{ $reply->message }}</p>
                        </div>
                    @empty
                        <p class="text-muted">No replies yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Reply / Feedback</h3>
                </div>
                <form method="POST" action="{{ route('admin.learning-issues.replies.store', $report->issue_report_id) }}">
                    @csrf
                    <div class="card-body">
                        @can('learning_issues.reply')
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    @foreach ($statuses as $key => $label)
                                        <option value="{{ $key }}" @selected($report->status === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Progress %</label>
                                <input type="number" min="0" max="100" name="progress_percent" value="{{ $report->progress_percent }}" class="form-control">
                            </div>
                        @endcan
                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror"></textarea>
                            @error('message')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-block">Send Reply</button>
                    </div>
                </form>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="mb-1"><strong>Student:</strong> {{ $report->student->student_number }} - {{ $report->student->user->username ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Teacher:</strong> {{ $report->assignedTeacher->user->username ?? 'Unassigned' }}</p>
                    <p class="mb-0"><strong>Deadline:</strong> {{ $report->deadline_at?->format('Y-m-d H:i') ?? 'None' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
