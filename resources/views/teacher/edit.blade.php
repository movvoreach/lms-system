@extends('admin.layouts.master')

@section('title', 'Edit Teacher')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7"><h1>Edit Teacher</h1></div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.teachers.index') }}">Teachers</a></li>
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
        <div class="card-header"><h3 class="card-title mb-0">Edit Teacher Information</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.teachers.update', $teacher->teacher_id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('teacher._form')
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-light border mr-2">Cancel</a>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header"><h3 class="card-title mb-0">Course Assignment History</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.teachers.courses.store', $teacher->teacher_id) }}" method="POST" class="mb-4">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label>Course <span class="text-danger">*</span></label>
                        <select name="course_id" class="form-control custom-select" required>
                            <option value="">-- Select course --</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Status</label>
                        <input type="text" name="status" class="form-control" value="assigned" maxlength="30">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-block">Assign Course</button>
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
                            <th>Assigned At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teacher->courseAssignments as $key => $assignment)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $assignment->course->title ?? 'N/A' }}</td>
                                <td>{{ $assignment->course->semester->semester_name ?? 'N/A' }}</td>
                                <td>{{ $assignment->course->semester->academicYear->year_label ?? 'N/A' }}</td>
                                <td><span class="badge badge-info">{{ $assignment->status }}</span></td>
                                <td>{{ $assignment->assigned_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No course assignments yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
