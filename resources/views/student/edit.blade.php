@extends('admin.layouts.master')

@section('title', 'Edit Student')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7"><h1>Edit Student</h1></div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header"><h3 class="card-title mb-0">Edit Student Information</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.students.update', $student->student_id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('student._form')
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-light border mr-2">Cancel</a>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header"><h3 class="card-title mb-0">Course Registration History</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.students.courses.store', $student->student_id) }}" method="POST" class="mb-4">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label>Course <span class="text-danger">*</span></label>
                        <select name="course_id" class="form-control custom-select" required>
                            <option value="">-- Select course --</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Academic Year</label>
                        <select name="academic_year_id" class="form-control custom-select">
                            <option value="">-- Select academic year --</option>
                            @foreach ($academicYears as $academicYear)
                                <option value="{{ $academicYear->academic_year_id }}">{{ $academicYear->year_label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Status</label>
                        <input type="text" name="status" class="form-control" value="registered" maxlength="30">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-block">Register Course</button>
                    </div>
                    <div class="col-12 mt-2">
                        <textarea name="notes" rows="2" class="form-control" placeholder="Notes"></textarea>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Course</th>
                            <th>Semester</th>
                            <th>Academic Year</th>
                            <th>Status</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($student->courseRegistrations as $key => $registration)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $registration->course->title ?? 'N/A' }}</td>
                                <td>{{ $registration->course->semester->semester_name ?? 'N/A' }}</td>
                                <td>{{ $registration->academicYear->year_label ?? $registration->course->semester->academicYear->year_label ?? 'N/A' }}</td>
                                <td><span class="badge badge-info">{{ $registration->status }}</span></td>
                                <td>{{ $registration->registered_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No course registrations yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
