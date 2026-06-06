@extends('admin.layouts.master')

@section('title', 'Manage Student Enrollment')

@section('content')
    @php
        $latestRecord = $student->academicYearRecords->sortByDesc('record_id')->first();
        $selectedDepartmentId = old('department_id', $student->department_id ?? $latestRecord?->department_id);
        $selectedAcademicYearId = old('academic_year_id', $latestRecord?->academic_year_id);
        $selectedStudyYear = old('study_year', $latestRecord?->study_year ?? 1);
        $selectedTermNumber = old('term_number', $latestRecord?->term_number ?? 1);
        $selectedSemesterId = old('semester_id', $latestRecord?->semester_id);
        $selectedCourseIds = collect(old('course_ids', $student->courseRegistrations->where('semester_id', $selectedSemesterId)->pluck('course_id')->all()))
            ->map(fn ($id) => (int) $id)
            ->all();
        $nextStudyYear = $latestRecord ? (int) $latestRecord->study_year : 1;
        $nextTermNumber = $latestRecord ? (int) $latestRecord->term_number + 1 : 1;

        if ($nextTermNumber > 2) {
            $nextTermNumber = 1;
            $nextStudyYear++;
        }
    @endphp

    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">Manage Enrollment</h1>
                    <p class="text-muted mb-0">{{ $student->student_number }} - {{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: ($student->user->username ?? 'Student') }}</p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.student-enrollments.index') }}">Student Enrollment</a></li>
                        <li class="breadcrumb-item active">{{ $student->student_number }}</li>
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
            <h3 class="card-title mb-0">Student Information</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <span class="text-muted d-block">Student Number</span>
                    <strong>{{ $student->student_number }}</strong>
                </div>
                <div class="col-md-3">
                    <span class="text-muted d-block">Name</span>
                    <strong>{{ trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) ?: '-' }}</strong>
                </div>
                <div class="col-md-3">
                    <span class="text-muted d-block">Email</span>
                    <strong>{{ $student->user->email ?? '-' }}</strong>
                </div>
                <div class="col-md-3">
                    <span class="text-muted d-block">Phone</span>
                    <strong>{{ $student->phone ?? '-' }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-0">Enroll Student</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.student-enrollments.store', $student->student_id) }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Department <span class="text-danger">*</span></label>
                            <select name="department_id" id="enrollmentDepartment" class="form-control custom-select" required>
                                <option value="">-- Select department --</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->department_id }}" @selected((int) $selectedDepartmentId === $department->department_id)>
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Academic Year <span class="text-danger">*</span></label>
                            <select name="academic_year_id" id="enrollmentAcademicYear" class="form-control custom-select" required>
                                <option value="">-- Select academic year --</option>
                                @foreach ($academicYears as $academicYear)
                                    <option value="{{ $academicYear->academic_year_id }}" @selected((int) $selectedAcademicYearId === $academicYear->academic_year_id)>
                                        {{ $academicYear->year_label }} ({{ $academicYear->status }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Study Year <span class="text-danger">*</span></label>
                            <select name="study_year" id="enrollmentStudyYear" class="form-control custom-select" required>
                                @foreach ([1, 2, 3, 4] as $year)
                                    <option value="{{ $year }}" @selected((int) $selectedStudyYear === $year)>Year {{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Semester <span class="text-danger">*</span></label>
                            <select name="term_number" id="enrollmentTerm" class="form-control custom-select" required>
                                <option value="1" @selected((int) $selectedTermNumber === 1)>Semester 1</option>
                                <option value="2" @selected((int) $selectedTermNumber === 2)>Semester 2</option>
                            </select>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="semester_id" id="enrollmentSemesterId" value="{{ $selectedSemesterId }}">

                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Available Courses</strong>
                        <small class="text-muted" id="enrollmentSemesterName"></small>
                    </div>

                    <div id="enrollmentCourses" data-selected='@json($selectedCourseIds)'>
                        <div class="text-muted">Select department, academic year, study year, and semester.</div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Notes</label>
                    <textarea name="notes" rows="2" class="form-control">{{ old('notes') }}</textarea>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Save Enrollment</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-0">Enrollment History</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Department</th>
                        <th>Academic Year</th>
                        <th>Level</th>
                        <th>Semester</th>
                        <th>Status</th>
                        <th>Promotion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($student->academicYearRecords->sortByDesc('record_id') as $key => $record)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $record->department->department_name ?? '-' }}</td>
                            <td>{{ $record->academicYear->year_label ?? '-' }}</td>
                            <td>Year {{ $record->study_year }}</td>
                            <td>{{ $record->semester->semester_name ?? 'Semester '.$record->term_number }}</td>
                            <td><span class="badge badge-info">{{ $record->status }}</span></td>
                            <td>{{ $record->promotion_type ?? 'initial' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No enrollment history yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-0">Registered Courses</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Course</th>
                        <th>Department</th>
                        <th>Academic Year</th>
                        <th>Level</th>
                        <th>Semester</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($student->courseRegistrations as $key => $registration)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $registration->course->title ?? '-' }}</td>
                            <td>{{ $registration->course->department->department_name ?? '-' }}</td>
                            <td>{{ $registration->academicYear->year_label ?? '-' }}</td>
                            <td>Year {{ $registration->study_year ?? '-' }}</td>
                            <td>{{ $registration->semester->semester_name ?? '-' }}</td>
                            <td><span class="badge badge-info">{{ $registration->status }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No registered courses yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($latestRecord && $nextStudyYear <= 4)
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title mb-0">Promote Student</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.student-enrollments.promote', $student->student_id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="record_id" value="{{ $latestRecord->record_id }}">

                    <div class="alert alert-light border">
                        Promote from Year {{ $latestRecord->study_year }} Semester {{ $latestRecord->term_number }}
                        to Year {{ $nextStudyYear }} Semester {{ $nextTermNumber }}.
                    </div>

                    <div id="promotionCourses"
                        data-department="{{ $latestRecord->department_id }}"
                        data-academic-year="{{ $latestRecord->academic_year_id }}"
                        data-study-year="{{ $nextStudyYear }}"
                        data-term-number="{{ $nextTermNumber }}">
                        <div class="text-muted">Loading next semester courses...</div>
                    </div>

                    <div class="form-group mt-3">
                        <label>Promotion Notes</label>
                        <textarea name="notes" rows="2" class="form-control"></textarea>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-success">Promote Student</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        $(function() {
            const selectedCourses = new Set(($('#enrollmentCourses').data('selected') || []).map(Number));

            function renderCourses(target, courses, selected = selectedCourses) {
                if (!courses.length) {
                    target.html('<div class="text-muted">No courses found for this department and semester.</div>');
                    return;
                }

                target.html(courses.map(function(course) {
                    const checked = selected.has(Number(course.id)) ? 'checked' : '';
                    const code = course.code ? `<span class="text-muted">(${course.code})</span>` : '';

                    return `
                        <label class="d-flex align-items-center border rounded px-3 py-2 mb-2">
                            <input type="checkbox" name="course_ids[]" value="${course.id}" class="mr-2" ${checked}>
                            <span>${course.title} ${code}</span>
                        </label>
                    `;
                }).join(''));
            }

            function loadEnrollmentCourses() {
                const values = {
                    department_id: $('#enrollmentDepartment').val(),
                    academic_year_id: $('#enrollmentAcademicYear').val(),
                    study_year: $('#enrollmentStudyYear').val(),
                    term_number: $('#enrollmentTerm').val()
                };

                if (!values.department_id || !values.academic_year_id || !values.study_year || !values.term_number) {
                    $('#enrollmentSemesterId').val('');
                    $('#enrollmentSemesterName').text('');
                    renderCourses($('#enrollmentCourses'), []);
                    return;
                }

                $('#enrollmentCourses').html('<div class="text-muted">Loading courses...</div>');

                $.get('{{ route('admin.students.enrollment.courses') }}', values)
                    .done(function(response) {
                        $('#enrollmentSemesterId').val(response.semester?.id || '');
                        $('#enrollmentSemesterName').text(response.semester?.name || 'Semester not found');
                        renderCourses($('#enrollmentCourses'), response.courses || []);
                    })
                    .fail(function() {
                        $('#enrollmentCourses').html('<div class="text-danger">Unable to load courses.</div>');
                    });
            }

            $('#enrollmentDepartment, #enrollmentAcademicYear, #enrollmentStudyYear, #enrollmentTerm')
                .on('change', loadEnrollmentCourses);

            loadEnrollmentCourses();

            const promotion = $('#promotionCourses');

            if (promotion.length) {
                $.get('{{ route('admin.students.enrollment.courses') }}', {
                    department_id: promotion.data('department'),
                    academic_year_id: promotion.data('academic-year'),
                    study_year: promotion.data('study-year'),
                    term_number: promotion.data('term-number')
                }).done(function(response) {
                    renderCourses(promotion, response.courses || [], new Set((response.courses || []).map(course => Number(course.id))));
                }).fail(function() {
                    promotion.html('<div class="text-danger">Unable to load next semester courses.</div>');
                });
            }
        });
    </script>
@endpush
