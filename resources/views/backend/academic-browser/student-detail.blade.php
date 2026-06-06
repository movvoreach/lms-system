@extends('admin.layouts.master')

@section('title', 'ព័ត៌មានសិស្ស')

@section('content')

@php
    $studentName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''))
        ?: ($student->user->username ?? 'N/A');

    $totalLessons = $course->lessonContents->count();

    $completedLessons = $student->lessonGrades
        ->where('passed', true)
        ->count();

    $averageScore = $student->lessonGrades->avg('score');

    $completionPercent = $totalLessons > 0
        ? round(($completedLessons / $totalLessons) * 100)
        : 0;
@endphp

<section class="content-header mt-4">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>{{ $studentName }}</h1>
                <p class="text-muted">
                    {{ $course->title }}
                </p>
            </div>

            <a href="{{ route('admin.courses.students.index', $course->id) }}"
                class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                ត្រឡប់ក្រោយ
            </a>
        </div>
    </div>
</section>

{{-- TOP SUMMARY --}}
<div class="row">

    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>មេរៀនសរុប</h5>
                <h2>{{ $totalLessons }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>មេរៀនបានបញ្ចប់</h5>
                <h2>{{ $completedLessons }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5>ពិន្ទុមធ្យម</h5>
                <h2>{{ number_format($averageScore, 2) }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5>ការបញ្ចប់វគ្គសិក្សា</h5>
                <h2>{{ $completionPercent }}%</h2>
            </div>
        </div>
    </div>

</div>

{{-- PROGRESS BAR --}}
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">
            ស្ថានភាពការសិក្សា
        </h3>
    </div>

    <div class="card-body">

        <div class="progress mb-3" style="height: 30px;">
            <div class="progress-bar bg-success"
                role="progressbar"
                style="width: {{ $completionPercent }}%">
                {{ $completionPercent }}%
            </div>
        </div>

        @if($completionPercent >= 100)
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                សិស្សបានបញ្ចប់វគ្គសិក្សា
            </div>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-clock"></i>
                សិស្សមិនទាន់បញ្ចប់វគ្គសិក្សា
            </div>
        @endif

    </div>
</div>

{{-- LESSON TABLE --}}
<div class="card shadow-sm">

    <div class="card-header">
        <h3 class="card-title">
            ព័ត៌មានមេរៀន និងពិន្ទុ
        </h3>
    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-bordered table-striped mb-0">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>មេរៀន</th>
                        <th>Quiz</th>
                        <th>Assignment</th>
                        <th>ពិន្ទុ</th>
                        <th>លទ្ធផល</th>
                        <th>មតិយោបល់</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($course->lessonContents as $key => $lesson)

                        @php
                            $grade = $student->lessonGrades
                                ->firstWhere('lesson_content_id', $lesson->id);

                            $score = $grade->score ?? 0;

                            $passScore = $lesson->passing_score ?? 50;

                            $passed = $score >= $passScore;
                        @endphp

                        <tr>

                            <td>{{ $key + 1 }}</td>

                            <td>
                                {{ $lesson->title }}
                            </td>

                            <td>
                                {{ $lesson->quiz_count ?? 0 }}
                            </td>

                            <td>
                                {{ $lesson->assignment_count ?? 0 }}
                            </td>

                            <td>
                                {{ number_format($score, 2) }}
                            </td>

                            <td>

                                @if($passed)
                                    <span class="badge badge-success">
                                        ជាប់
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        មិនទាន់ជាប់
                                    </span>
                                @endif

                            </td>

                            <td>
                                {{ $grade->feedback ?? 'គ្មានមតិយោបល់' }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                មិនមានមេរៀន
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- CERTIFICATE --}}
<div class="card shadow-sm">

    <div class="card-header">
        <h3 class="card-title">
            ស្ថានភាពវិញ្ញាបនបត្រ
        </h3>
    </div>

    <div class="card-body">

        @if($completionPercent >= 100)

            <div class="alert alert-success">
                <i class="fas fa-award"></i>
                សិស្សមានសិទ្ធិទទួលវិញ្ញាបនបត្រ
            </div>

        @else

            <div class="alert alert-danger">
                <i class="fas fa-times-circle"></i>
                សិស្សមិនទាន់មានសិទ្ធិទទួលវិញ្ញាបនបត្រ
            </div>

        @endif

    </div>

</div>

@endsection
