@extends('admin.layouts.master')

@section('title', 'Grade Student')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">Grade Student</h1>
                    <p class="text-muted mb-0">
                        {{ $registration->student->student_number }} -
                        {{ $registration->student->user->username ?? 'Student' }} /
                        {{ $registration->course->title ?? 'Course' }}
                    </p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.course-grades.index') }}">Grades</a></li>
                        <li class="breadcrumb-item active">Grade</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('admin.course-grades.update', $registration->registration_id) }}">
                @csrf
                @method('PUT')

                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">Module Grades</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Module</th>
                                    <th>Lesson</th>
                                    <th>Required</th>
                                    <th>Score</th>
                                    <th>Pass Score</th>
                                    <th>Feedback</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($registration->course->lessonContents as $lesson)
                                    @php($grade = $grades->get($lesson->id))
                                    <tr>
                                        <td>
                                            {{ $lesson->module_number }}
                                            <br><small class="text-muted">{{ $lesson->module_title }}</small>
                                        </td>
                                        <td>
                                            {{ $lesson->title }}
                                            @if ($grade)
                                                <br>
                                                <span class="badge badge-{{ $grade->passed ? 'success' : 'danger' }}">
                                                    {{ $grade->passed ? 'Passed' : 'Not Passed' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $lesson->completion_required ? 'Yes' : 'No' }}</td>
                                        <td style="width: 140px;">
                                            <input type="number" min="0" step="0.01"
                                                max="{{ $lesson->max_score ?? 999999.99 }}"
                                                name="grades[{{ $lesson->id }}][score]"
                                                value="{{ old("grades.{$lesson->id}.score", $grade->score ?? '') }}"
                                                class="form-control">
                                        </td>
                                        <td>
                                            {{ number_format((float) ($lesson->passing_score ?? $lesson->max_score ?? 0), 2) }}
                                            @if ($lesson->max_score)
                                                / {{ number_format((float) $lesson->max_score, 2) }}
                                            @endif
                                        </td>
                                        <td>
                                            <input type="text" name="grades[{{ $lesson->id }}][feedback]"
                                                value="{{ old("grades.{$lesson->id}.feedback", $grade->feedback ?? '') }}"
                                                class="form-control">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.course-grades.index') }}" class="btn btn-light border">Back</a>
                        <button type="submit" class="btn btn-primary">Save Grades</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Completion</h3>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        Passed required lessons:
                        <strong>{{ $completion['passed'] }} / {{ $completion['required'] }}</strong>
                    </p>
                    <span class="badge badge-{{ $completion['completed'] ? 'success' : 'warning' }}">
                        {{ $completion['completed'] ? 'Course Completed' : 'Not Complete' }}
                    </span>

                    <hr>

                    <p class="mb-2">Certificate request:</p>
                    @if ($certificateRequest)
                        <span class="badge badge-{{ $certificateRequest->status === 'approved' ? 'success' : ($certificateRequest->status === 'rejected' ? 'danger' : 'info') }}">
                            {{ ucfirst($certificateRequest->status) }}
                        </span>
                        @if ($certificateRequest->admin_note)
                            <p class="text-muted mt-2 mb-0">{{ $certificateRequest->admin_note }}</p>
                        @endif
                    @else
                        <span class="text-muted">No request yet.</span>
                    @endif

                    @if ($completion['completed'] && (! $certificateRequest || $certificateRequest->status === 'rejected'))
                        <form method="POST" action="{{ route('admin.course-grades.certificate-request', $registration->registration_id) }}" class="mt-3">
                            @csrf
                            <div class="form-group">
                                <label>Teacher note</label>
                                <textarea name="teacher_note" rows="3" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">
                                Request Certificate
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
