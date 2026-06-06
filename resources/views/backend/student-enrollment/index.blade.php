@extends('admin.layouts.master')

@section('title', 'Student Enrollment')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">Student Enrollment</h1>
                    <p class="text-muted mb-0">Manage academic placement, registered courses, and promotion.</p>
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
            <h3 class="card-title mb-0">Students</h3>
        </div>

        <div class="card-body table-responsive">
            <table id="studentEnrollmentTable" class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Number</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Current Level</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $key => $student)
                        @php($currentRecord = $student->academicYearRecords->sortByDesc('record_id')->first())
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $student->student_number }}</td>
                            <td>{{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: ($student->user->username ?? '-') }}</td>
                            <td>{{ $student->department->department_name ?? $currentRecord?->department?->department_name ?? '-' }}</td>
                            <td>
                                @if ($currentRecord)
                                    Year {{ $currentRecord->study_year }} / Semester {{ $currentRecord->term_number }}
                                    <br><small class="text-muted">{{ $currentRecord->academicYear->year_label ?? '' }}</small>
                                @else
                                    <span class="text-muted">Not enrolled</span>
                                @endif
                            </td>
                            <td><span class="badge badge-info">{{ $student->status ?? '-' }}</span></td>
                            <td>
                                <a href="{{ route('admin.student-enrollments.manage', $student->student_id) }}" class="btn btn-primary btn-sm">
                                    Enrollment
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#studentEnrollmentTable').DataTable({
                responsive: true,
                autoWidth: false,
                order: [[0, 'asc']],
                columnDefs: [{ targets: -1, orderable: false, searchable: false }]
            });
        });
    </script>
@endpush
