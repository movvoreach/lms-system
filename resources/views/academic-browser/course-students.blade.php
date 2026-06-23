@extends('admin.layouts.master')

@section('title', 'សិស្សក្នុងវគ្គសិក្សា')

@push('styles')
    <style>
        .student-action-menu {
            border: 1px solid #e1e5ef;
            border-radius: 4px;
            box-shadow: 0 10px 22px rgba(31, 41, 70, .12);
            min-width: 220px;
            padding: 0;
        }

        .student-action-menu .dropdown-item {
            align-items: center;
            color: #3d445f;
            display: flex;
            font-size: 15px;
            gap: 10px;
            justify-content: space-between;
            padding: 13px 16px;
        }

        .student-action-menu .dropdown-item span {
            align-items: center;
            display: inline-flex;
            gap: 8px;
        }
    </style>
@endpush

@section('content')

    @php
        $teacher = $course->teacherAssignments->first()?->teacher;

        $teacherName = $teacher
            ? trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? '')) ?: ($teacher->user->username ?? 'គ្មាន')
            : 'គ្មាន';
    @endphp

    <section class="content-header mt-4 px-0">

        <div class="container-fluid px-0">

            <div class="row mb-2 align-items-center">

                <div class="col-sm-7">

                    <h1 class="mb-1">
                        {{ $course->title }}
                    </h1>

                    <p class="text-muted mb-0">

                        {{ $course->department->department_name ?? 'មិនមាននាយកដ្ឋាន' }}

                        @if ($course->semester)
                            / {{ $course->semester->semester_name }}
                        @endif

                    </p>

                </div>

                <div class="col-sm-5">

                    <ol class="breadcrumb float-sm-right mb-0">

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.departments.index') }}">
                                នាយកដ្ឋាន
                            </a>
                        </li>

                        @if ($course->department)

                            <li class="breadcrumb-item">

                                <a href="{{ route('admin.departments.courses.index', $course->department->department_id) }}">
                                    វគ្គសិក្សា
                                </a>

                            </li>

                        @endif

                        <li class="breadcrumb-item active">
                            សិស្ស
                        </li>

                    </ol>

                </div>

            </div>

        </div>

    </section>

    <div class="card shadow-sm mb-3">

        <div class="card-body py-3">

            <div class="row">

                <div class="col-md-3">

                    <span class="text-muted d-block">
                        កូដវគ្គសិក្សា
                    </span>

                    <strong>
                        {{ $course->code ?? 'គ្មាន' }}
                    </strong>

                </div>

                <div class="col-md-3">

                    <span class="text-muted d-block">
                        គ្រូបង្រៀន
                    </span>

                    <strong>
                        {{ $teacherName }}
                    </strong>

                </div>

                <div class="col-md-3">

                    <span class="text-muted d-block">
                        ឆ្នាំសិក្សា
                    </span>

                    <strong>
                        {{ $course->semester->academicYear->year_label ?? 'គ្មាន' }}
                    </strong>

                </div>

                <div class="col-md-3">

                    <span class="text-muted d-block">
                        ចំនួនសិស្សចុះឈ្មោះ
                    </span>

                    <strong>
                        {{ $registrations->count() }}
                    </strong>

                </div>

            </div>

        </div>

    </div>

    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    @if (session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif

    @if (session('info'))

        <div class="alert alert-info">
            {{ session('info') }}
        </div>

    @endif

    <div class="card shadow-sm mb-3">

        <div class="card-body d-flex flex-wrap align-items-center justify-content-between">

            <div>

                <span class="text-muted d-block">
                    ស្ថានភាពសំណើវិញ្ញាបនបត្រ
                </span>

                @if ($certificateStatus === 'done')

                    <strong class="text-success">
                        បានបញ្ចប់ - អ្នកគ្រប់គ្រងបានអនុម័តសំណើវិញ្ញាបនបត្រ។
                    </strong>

                @elseif ($certificateStatus === 'processing')

                    <strong class="text-info">
                        កំពុងដំណើរការ - កំពុងរង់ចាំការអនុម័ត។
                    </strong>

                @elseif ($certificateStatus === 'ready')

                    <strong class="text-success">
                        រួចរាល់ - សិស្សទាំងអស់បានបញ្ចប់វគ្គសិក្សា។
                    </strong>

                @elseif ($certificateStatus === 'rejected')

                    <strong class="text-danger">
                        សំណើវិញ្ញាបនបត្រមួយចំនួនត្រូវបានបដិសេធ។
                    </strong>

                @else

                    <strong class="text-muted">
                        មិនទាន់រួចរាល់ - សិស្សទាំងអស់ត្រូវបញ្ចប់មេរៀនជាមុនសិន។
                    </strong>

                @endif

                <div class="text-muted small mt-1">

                    បានអនុម័ត៖ {{ $approvedCertificateCount }} /

                    កំពុងរង់ចាំ៖ {{ $pendingCertificateCount }} /

                    មិនទាន់មាន៖ {{ $missingCertificateRequestsCount }}

                </div>

            </div>

            @can('certificates.request')

                @if ($certificateStatus === 'ready' && $missingCertificateRequestsCount > 0)

                    <form method="POST"
                        action="{{ route('admin.course-grades.course-certificate-requests') }}"
                        class="mt-3 mt-md-0">

                        @csrf

                        <input type="hidden"
                            name="course_id"
                            value="{{ $course->id }}">

                        <button type="submit"
                            class="btn btn-success">

                            <i class="fas fa-certificate mr-1"></i>

                            ស្នើវិញ្ញាបនបត្រ

                        </button>

                    </form>

                @elseif ($certificateStatus === 'processing')

                    <button type="button"
                        class="btn btn-info mt-3 mt-md-0"
                        disabled>

                        <i class="fas fa-hourglass-half mr-1"></i>

                        រង់ចាំការអនុម័ត

                    </button>

                @elseif ($certificateStatus === 'done')

                    <button type="button"
                        class="btn btn-success mt-3 mt-md-0"
                        disabled>

                        <i class="fas fa-check-circle mr-1"></i>

                        បានបញ្ចប់

                    </button>

                @endif

            @endcan

        </div>

    </div>

    <div class="card shadow-sm">

        <div class="card-header d-flex align-items-center">

            <h3 class="card-title mb-0">
                សិស្សក្នុងវគ្គសិក្សា
            </h3>

            <div class="card-tools ml-auto">

                @if ($course->department)

                    <a href="{{ route('admin.departments.courses.index', $course->department->department_id) }}"
                        class="btn btn-light border btn-sm">

                        <i class="fas fa-arrow-left mr-1"></i>

                        ត្រឡប់ក្រោយ

                    </a>

                @endif

            </div>

        </div>

        @if ($course->lessonContents->isNotEmpty())

            <div class="card-body border-bottom pb-2">

                <form method="GET"
                    action="{{ route('admin.courses.students.index', $course->id) }}">

                    <div class="form-row align-items-end">

                        <div class="form-group col-md-8">

                            <label>
                                មេរៀន / លម្អិតវគ្គសិក្សា
                            </label>

                            <select name="lesson_content_id"
                                class="form-control"
                                onchange="this.form.submit()">

                                @foreach ($course->lessonContents as $lesson)

                                    <option value="{{ $lesson->id }}"
                                        @selected($selectedLessonId === $lesson->id)>

                                        {{ $lesson->module_number }}.
                                        {{ $lesson->title }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="form-group col-md-4">

                            <button type="submit"
                                class="btn btn-primary btn-block">

                                <i class="fas fa-filter mr-1"></i>

                                ជ្រើសរើសមេរៀន

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        @endif

        <div class="card-body">

            @if ($selectedLesson)

                <form method="POST"
                    action="{{ route('admin.course-grades.bulk-update') }}"
                    id="courseStudentScoresForm">

                    @csrf
                    @method('PUT')

                    <input type="hidden"
                        name="course_id"
                        value="{{ $course->id }}">

                    <input type="hidden"
                        name="lesson_content_id"
                        value="{{ $selectedLesson->id }}">

                    <input type="hidden"
                        name="stay_on_page"
                        value="1">

                    <div class="table-responsive">

                        <table id="courseStudentsTable"
                            class="table table-bordered table-striped w-100">

                            <thead>

                                <tr>

                                    <th>ល.រ</th>
                                    <th>លេខសិស្ស</th>
                                    <th>ឈ្មោះ</th>
                                    <th>ភេទ</th>
                                    <th>ទំនាក់ទំនង</th>
                                    <th>ឆ្នាំសិក្សា</th>
                                    <th>ស្ថានភាព</th>
                                    <th>ពិន្ទុ</th>
                                    <th>លទ្ធផល</th>
                                    <th>មតិយោបល់</th>
                                    <th>សកម្មភាព</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse ($registrations as $key => $registration)

                                    @php

                                        $student = $registration->student;

                                        $studentName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''))
                                            ?: ($student->user->username ?? 'គ្មាន');

                                        $grade = $student?->lessonGrades?->firstWhere(
                                            'lesson_content_id',
                                            $selectedLesson->id,
                                        );

                                        $passingScore = $selectedLesson->passing_score
                                            ?? $selectedLesson->max_score
                                            ?? 0;

                                        $score = old(
                                            "grades.{$registration->registration_id}.score",
                                            $grade->score ?? '',
                                        );

                                        $passed = filled($score)
                                            && (float) $score >= (float) $passingScore;

                                    @endphp

                                    <tr>

                                        <td>{{ $key + 1 }}</td>

                                        <td>
                                            {{ $student->student_number ?? 'គ្មាន' }}
                                        </td>

                                        <td>

                                            {{ $studentName }}

                                            <br>

                                            <small class="text-muted">
                                                {{ $student->user->username ?? '' }}
                                            </small>

                                        </td>

                                        <td>
                                            {{ $student->gender ?? 'គ្មាន' }}
                                        </td>

                                        <td>

                                            {{ $student->phone ?? 'គ្មាន' }}

                                            <br>

                                            <small class="text-muted">
                                                {{ $student->user->email ?? '' }}
                                            </small>

                                        </td>

                                        <td>

                                            {{ $registration->academicYear->year_label ?? $course->semester->academicYear->year_label ?? 'គ្មាន' }}

                                        </td>

                                        <td>

                                            <span class="badge badge-info">
                                                {{ $registration->status ?? 'បានចុះឈ្មោះ' }}
                                            </span>

                                        </td>

                                        <td style="min-width: 110px;">

                                            <input type="number"
                                                min="0"
                                                step="0.01"

                                                @if ($selectedLesson->max_score)
                                                    max="{{ $selectedLesson->max_score }}"
                                                @endif

                                                name="grades[{{ $registration->registration_id }}][score]"

                                                value="{{ $score }}"

                                                class="form-control form-control-sm js-score"

                                                data-pass-score="{{ $passingScore }}">

                                        </td>

                                        <td style="min-width: 110px;">

                                            <input type="text"
                                                class="form-control form-control-sm js-result"

                                                value="{{ $passed ? 'ជាប់' : 'មិនទាន់ជាប់' }}"

                                                readonly>

                                        </td>

                                        <td style="min-width: 160px;">

                                            <input type="text"

                                                name="grades[{{ $registration->registration_id }}][feedback]"

                                                value="{{ old("grades.{$registration->registration_id}.feedback", $grade->feedback ?? '') }}"

                                                class="form-control form-control-sm js-feedback"

                                                placeholder="មតិយោបល់">

                                        </td>

                                        <td>

                                            @if ($student)

                                                <div class="dropdown">

                                                    <button type="button"
                                                        class="btn btn-primary btn-sm"
                                                        data-toggle="dropdown">

                                                        សកម្មភាព

                                                        <i class="fas fa-ellipsis-v ml-1"></i>

                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right student-action-menu">

                                                        <a href="{{ route('admin.courses.students.show', [$course->id, $student->student_id]) }}"
                                                            class="dropdown-item">

                                                            <span>
                                                                <i class="fas fa-eye"></i>
                                                                មើលលម្អិត
                                                            </span>

                                                            <i class="fas fa-chevron-right"></i>

                                                        </a>

                                                        <a href="#"
                                                            class="dropdown-item js-open-score-modal"
                                                            data-student-name="{{ $studentName }}"
                                                            data-student-number="{{ $student->student_number ?? '' }}"
                                                            data-score="{{ $score }}"
                                                            data-feedback="{{ old("grades.{$registration->registration_id}.feedback", $grade->feedback ?? '') }}"
                                                            data-pass-score="{{ $passingScore }}"
                                                            data-max-score="{{ $selectedLesson->max_score }}"
                                                            data-lesson-title="{{ $selectedLesson->title }}">

                                                            <span>
                                                                <i class="fas fa-plus-circle"></i>
                                                                បញ្ចូលពិន្ទុ
                                                            </span>

                                                            <i class="fas fa-chevron-right"></i>

                                                        </a>

                                                        <a href="{{ route('admin.students.edit', $student->student_id) }}"
                                                            class="dropdown-item">

                                                            <span>
                                                                <i class="fas fa-file"></i>
                                                                កែប្រែសិស្ស
                                                            </span>

                                                            <i class="fas fa-chevron-right"></i>

                                                        </a>

                                                    </div>

                                                </div>

                                            @endif

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="11"
                                            class="text-center text-muted py-4">

                                            មិនមានសិស្សក្នុងវគ្គសិក្សានេះទេ។

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    <div class="text-right mt-3">

                        <button type="submit"
                            class="btn btn-success"
                            @disabled($registrations->isEmpty())>

                            <i class="fas fa-save mr-1"></i>

                            រក្សាទុកពិន្ទុ

                        </button>

                    </div>

                </form>

            @else

                <div class="text-center text-muted py-4">

                    មិនមានមេរៀន ឬ លម្អិតវគ្គសិក្សាទេ។
                    សូមបង្កើតមេរៀនមុនពេលបញ្ចូលពិន្ទុ។

                </div>

            @endif

        </div>

    </div>

    @if ($selectedLesson)
        <div class="modal fade"
            id="scoreModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="scoreModalLabel"
            aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered"
                role="document">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title"
                            id="scoreModalLabel">
                            បញ្ចូលពិន្ទុ
                        </h5>

                        <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <span class="text-muted d-block">សិស្ស</span>
                            <strong id="scoreModalStudent">-</strong>
                        </div>

                        <div class="mb-3">
                            <span class="text-muted d-block">មេរៀន</span>
                            <strong id="scoreModalLesson">{{ $selectedLesson->title }}</strong>
                        </div>

                        <div class="form-group">
                            <label for="scoreModalScore">ពិន្ទុ</label>
                            <input type="number"
                                min="0"
                                step="0.01"
                                @if ($selectedLesson->max_score) max="{{ $selectedLesson->max_score }}" @endif
                                class="form-control"
                                id="scoreModalScore">
                            <small class="form-text text-muted">
                                ពិន្ទុជាប់: {{ $selectedLesson->passing_score ?? $selectedLesson->max_score ?? 0 }}
                                @if ($selectedLesson->max_score)
                                    / ពិន្ទុអតិបរមា: {{ $selectedLesson->max_score }}
                                @endif
                            </small>
                        </div>

                        <div class="form-group mb-0">
                            <label for="scoreModalFeedback">មតិយោបល់</label>
                            <textarea class="form-control"
                                id="scoreModalFeedback"
                                rows="3"></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button"
                            class="btn btn-light border"
                            data-dismiss="modal">
                            បោះបង់
                        </button>

                        <button type="button"
                            class="btn btn-success"
                            id="scoreModalSave">
                            <i class="fas fa-save mr-1"></i>
                            រក្សាទុក
                        </button>
                    </div>

                </div>

            </div>

        </div>
    @endif

    @if (session('success') || session('error') || session('info'))
        <div class="modal fade"
            id="messageModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="messageModalLabel"
            aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered"
                role="document">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="messageModalLabel">
                            {{ session('error') ? 'មានបញ្ហា' : 'ជោគជ័យ' }}
                        </h5>

                        <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        {{ session('success') ?? session('error') ?? session('info') }}
                    </div>

                    <div class="modal-footer">
                        <button type="button"
                            class="btn {{ session('error') ? 'btn-danger' : 'btn-success' }}"
                            data-dismiss="modal">
                            យល់ព្រម
                        </button>
                    </div>

                </div>

            </div>

        </div>
    @endif

@endsection

@push('scripts')

    <script>

        $(function() {

            let activeScoreRow = null;

            $('#messageModal').modal('show');

            $('#courseStudentsTable').DataTable({

                responsive: false,
                scrollX: true,
                autoWidth: false,
                paging: false,

                order: [
                    [0, 'asc']
                ],

                columnDefs: [{
                    targets: -1,
                    orderable: false,
                    searchable: false
                }],

                language: {

                    search: "ស្វែងរក:",

                    zeroRecords: "មិនមានទិន្នន័យ",

                    info: "បង្ហាញ _START_ ដល់ _END_ នៃ _TOTAL_ ជួរ",

                    infoEmpty: "គ្មានទិន្នន័យ",

                    infoFiltered: "(ចម្រោះពី _MAX_ ជួរ)",

                    paginate: {
                        first: "ដំបូង",
                        last: "ចុងក្រោយ",
                        next: "បន្ទាប់",
                        previous: "មុន"
                    }

                }

            });

            $(document).on('click', '.js-open-score-modal', function(event) {

                event.preventDefault();

                const link = $(this);
                activeScoreRow = link.closest('tr');

                $('#scoreModalStudent').text(
                    [link.data('student-name'), link.data('student-number')]
                        .filter(Boolean)
                        .join(' - ')
                );
                $('#scoreModalLesson').text(link.data('lesson-title') || '');
                $('#scoreModalScore')
                    .val(activeScoreRow.find('.js-score').val() || link.data('score') || '')
                    .attr('max', link.data('max-score') || null);
                $('#scoreModalFeedback').val(activeScoreRow.find('.js-feedback').val() || link.data('feedback') || '');

                $('#scoreModal').modal('show');

            });

            $('#scoreModalSave').on('click', function() {

                if (!activeScoreRow) {
                    return;
                }

                activeScoreRow.find('.js-score')
                    .val($('#scoreModalScore').val())
                    .trigger('input');

                activeScoreRow.find('.js-feedback')
                    .val($('#scoreModalFeedback').val());

                $('#scoreModal').modal('hide');
                $('#courseStudentScoresForm').trigger('submit');

            });

            $(document).on('input', '.js-score', function() {

                const input = $(this);

                const score = parseFloat(input.val());

                const passScore = parseFloat(input.data('pass-score')) || 0;

                const passed = !Number.isNaN(score) && score >= passScore;

                input.closest('tr')
                    .find('.js-result')
                    .val(passed ? 'ជាប់' : 'មិនទាន់ជាប់');

            });

        });

    </script>

@endpush
