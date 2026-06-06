@extends('admin.layouts.master')

@section('title', 'កែប្រែគ្រូបង្រៀន')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7"><h1>កែប្រែគ្រូបង្រៀន</h1></div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.teachers.index') }}">គ្រូបង្រៀន</a></li>
                        <li class="breadcrumb-item active">កែប្រែ</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-0">ព័ត៌មានគ្រូបង្រៀន</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.teachers.update', $teacher->teacher_id) }}" method="POST">
                @csrf
                @method('PUT')

                @include('teacher._form')

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-light border mr-2">
                        បោះបង់
                    </a>
                    <button type="submit" class="btn btn-warning">
                        កែប្រែ
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-0">ប្រវត្តិការបង្រៀនមុខវិជ្ជា</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.teachers.courses.store', $teacher->teacher_id) }}" method="POST" class="mb-4">
                @csrf

                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label>មុខវិជ្ជា <span class="text-danger">*</span></label>
                        <select name="course_id" class="form-control custom-select" required>
                            <option value="">-- ជ្រើសរើសមុខវិជ្ជា --</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>ស្ថានភាព</label>
                        <input type="text" name="status" class="form-control" value="assigned" maxlength="30">
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-block">
                            ចាត់តាំងមុខវិជ្ជា
                        </button>
                    </div>

                    <div class="col-12 mt-2">
                        <textarea name="notes" rows="2" class="form-control" placeholder="កំណត់ចំណាំ"></textarea>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>មុខវិជ្ជា</th>
                            <th>ឆមាស</th>
                            <th>ឆ្នាំសិក្សា</th>
                            <th>ស្ថានភាព</th>
                            <th>ថ្ងៃចាត់តាំង</th>
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
                                <td colspan="6" class="text-center text-muted">
                                    មិនទាន់មានការចាត់តាំងមុខវិជ្ជា
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
