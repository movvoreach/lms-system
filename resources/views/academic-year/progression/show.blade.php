@extends('admin.layouts.master')

@section('title', 'Academic Year History')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">{{ $academicYear->year_label }} History</h1>
                    <p class="text-muted mb-0">These records are historical and should remain accessible permanently.</p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.academic-progression.index') }}">Academic Progression</a></li>
                        <li class="breadcrumb-item active">{{ $academicYear->year_label }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-0">Student Records</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student No.</th>
                        <th>Name</th>
                        <th>User</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Promotion</th>
                        <th>Previous Year</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $key => $record)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $record->student->student_number ?? 'N/A' }}</td>
                            <td>{{ trim(($record->student->first_name ?? '') . ' ' . ($record->student->last_name ?? '')) ?: 'N/A' }}</td>
                            <td>{{ $record->student->user->username ?? 'N/A' }}</td>
                            <td>{{ $record->course->title ?? 'N/A' }}</td>
                            <td><span class="badge badge-info">{{ $record->status }}</span></td>
                            <td>{{ $record->promotion_type ?? 'N/A' }}</td>
                            <td>{{ $record->previousRecord->academicYear->year_label ?? 'Initial record' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No student records for this academic year.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
