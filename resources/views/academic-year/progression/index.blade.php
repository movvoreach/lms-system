@extends('admin.layouts.master')

@section('title', 'Academic Progression')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">Academic Progression</h1>
                    <p class="text-muted mb-0">Academic year archives preserve every student record permanently.</p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Academic Progression</li>
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

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-0">Academic Year Archive</h3>
            <div class="card-tools">
                <a href="{{ route('admin.academic-progression.promote') }}" class="btn btn-primary btn-sm">
                    Promote Students
                </a>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Dates</th>
                        <th>Status</th>
                        <th>Student Records</th>
                        <th>Archive</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($academicYears as $year)
                        <tr>
                            <td>{{ $year->year_label }}</td>
                            <td>{{ $year->start_date?->format('Y-m-d') }} - {{ $year->end_date?->format('Y-m-d') }}</td>
                            <td><span class="badge badge-secondary">{{ ucfirst($year->status) }}</span></td>
                            <td>{{ $year->student_records_count }}</td>
                            <td>
                                <a href="{{ route('admin.academic-progression.show', $year->academic_year_id) }}"
                                    class="btn btn-info btn-sm">
                                    View History
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No academic years found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
