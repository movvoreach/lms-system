@extends('admin.layouts.master')

@section('title', 'បញ្ចូលពិន្ទុសិស្ស')

@section('content')
    @php
        $firstRegistration = $registrations->first();
        $academicYear = $firstRegistration?->academicYear?->year_label
            ?? $selectedCourse?->semester?->academicYear?->year_label
            ?? now()->year;
        $sheetDate = now()->format('d/m/Y');
    @endphp

    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="grade-sheet card border-0 shadow-sm">
                <div class="grade-sheet-ribbon">បញ្ជី</div>

                <div class="grade-sheet-header text-center">
                    <h1>បញ្ចូលពិន្ទុសិស្ស</h1>
                    <p>វគ្គសិក្សា: {{ $selectedCourse?->title ?? 'សូមជ្រើសរើសវគ្គសិក្សា' }}</p>
                    <p>មេរៀន/លម្អិត: {{ $selectedLesson?->title ?? 'សូមជ្រើសរើសលម្អិតវគ្គសិក្សា' }}</p>
                </div>

                <form method="GET" action="{{ route('admin.course-grades.index') }}" class="grade-filter">
                    <div class="form-row">
                        <div class="form-group col-lg-5">
                            <label>វគ្គសិក្សា</label>
                            <select name="course_id" class="form-control" onchange="this.form.submit()">
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" @selected($selectedCourseId === $course->id)>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-5">
                            <label>លម្អិតវគ្គសិក្សា / មេរៀន</label>
                            <select name="lesson_content_id" class="form-control" onchange="this.form.submit()">
                                @foreach ($courseDetails as $detail)
                                    <option value="{{ $detail->id }}" @selected($selectedLessonId === $detail->id)>
                                        {{ $detail->module_number }}. {{ $detail->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-filter mr-1"></i> បង្ហាញ
                            </button>
                        </div>
                    </div>
                </form>

                <div class="grade-sheet-meta">
                    <div>
                        <p>លេខសម្គាល់វគ្គសិក្សា: {{ $selectedCourse?->code ?? sprintf('%03d', $selectedCourse?->id ?? 0) }}</p>
                        <p>ពិន្ទុអតិបរមា: {{ $selectedLesson?->max_score ? number_format((float) $selectedLesson->max_score, 2) : 'មិនកំណត់' }}</p>
                        <p>ពិន្ទុឆ្លង: {{ $selectedLesson?->passing_score ? number_format((float) $selectedLesson->passing_score, 2) : ($selectedLesson?->max_score ? number_format((float) $selectedLesson->max_score, 2) : '0.00') }}</p>
                    </div>
                    <div class="text-right">
                        <p>ឆ្នាំសិក្សា: {{ $academicYear }}</p>
                        <p>កាលបរិច្ឆេទ: {{ $sheetDate }}</p>
                        <p>ចំនួនសិស្ស: {{ $registrations->count() }} នាក់</p>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success mx-4">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger mx-4">{{ session('error') }}</div>
                @endif

                @if ($selectedCourse && $selectedLesson)
                    <form method="POST" action="{{ route('admin.course-grades.bulk-update') }}" id="gradeSheetForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="course_id" value="{{ $selectedCourse->id }}">
                        <input type="hidden" name="lesson_content_id" value="{{ $selectedLesson->id }}">

                        <div class="table-responsive grade-sheet-table-wrap">
                            <table id="courseGradeTable" class="table grade-sheet-table mb-0">
                                <thead>
                                    <tr>
                                        <th>លេខរៀង</th>
                                        <th>គោត្តនាម-នាម<br><small>អត្តលេខ</small></th>
                                        <th>ភេទ</th>
                                        <th>វគ្គសិក្សា<br><small>លម្អិត</small></th>
                                        <th>ស្ថានភាព</th>
                                        <th>ពិន្ទុ</th>
                                        <th>លទ្ធផល</th>
                                        <th>គ្រប់គ្រាន់</th>
                                        <th>សំគាល់</th>
                                        <th>សកម្មភាព</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($registrations as $key => $registration)
                                        @php
                                            $student = $registration->student;
                                            $studentName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''))
                                                ?: ($student->user->username ?? 'មិនមាន');
                                            $grade = $student?->lessonGrades?->firstWhere('lesson_content_id', $selectedLesson->id);
                                            $passingScore = $selectedLesson->passing_score ?? $selectedLesson->max_score ?? 0;
                                            $score = old("grades.{$registration->registration_id}.score", $grade->score ?? '');
                                            $passed = filled($score) && (float) $score >= (float) $passingScore;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td>
                                                <strong>{{ $studentName }}</strong>
                                                <span>{{ $student->student_number ?? 'N/A' }}</span>
                                            </td>
                                            <td class="text-center">{{ $student->gender ?? '-' }}</td>
                                            <td>
                                                <strong>{{ \Illuminate\Support\Str::limit($selectedCourse->title ?? 'N/A', 46) }}</strong>
                                                <span>{{ $selectedLesson->module_number }}. {{ \Illuminate\Support\Str::limit($selectedLesson->title, 52) }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ ucfirst($registration->status ?? 'registered') }}</strong>
                                                <span>{{ $registration->completed_at?->format('d/m/Y') ?? 'កំពុងសិក្សា' }}</span>
                                            </td>
                                            <td class="score-cell">
                                                <input type="number" min="0" step="0.01"
                                                    @if ($selectedLesson->max_score) max="{{ $selectedLesson->max_score }}" @endif
                                                    name="grades[{{ $registration->registration_id }}][score]"
                                                    value="{{ $score }}"
                                                    class="form-control grade-input text-center js-score"
                                                    data-pass-score="{{ $passingScore }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control grade-input text-center js-result"
                                                    value="{{ $passed ? 'ជាប់' : 'មិនទាន់' }}" readonly>
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" class="grade-check js-passed" @checked($passed) disabled>
                                            </td>
                                            <td>
                                                <input type="text"
                                                    name="grades[{{ $registration->registration_id }}][feedback]"
                                                    value="{{ old("grades.{$registration->registration_id}.feedback", $grade->feedback ?? '') }}"
                                                    class="form-control grade-input" placeholder="សំគាល់">
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.course-grades.edit', $registration->registration_id) }}"
                                                    class="btn btn-primary btn-sm grade-action" title="មើលលម្អិត">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-5 text-muted">មិនមានសិស្សក្នុងវគ្គសិក្សានេះ</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="grade-sheet-footer">
                            <button type="submit" class="btn btn-success px-4" @disabled($registrations->isEmpty())>
                                <i class="fas fa-save mr-1"></i> រក្សាទុកពិន្ទុ
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center text-muted py-5">
                        សូមបង្កើតវគ្គសិក្សា និងលម្អិតវគ្គសិក្សា មុនពេលបញ្ចូលពិន្ទុ។
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .grade-sheet {
            color: #39405d;
            font-family: "Battambang", "Source Sans Pro", sans-serif;
            overflow: hidden;
            position: relative;
        }

        .grade-sheet-ribbon {
            background: #f2142d;
            color: #fff;
            font-size: 22px;
            font-weight: 700;
            height: 104px;
            left: 24px;
            padding-top: 34px;
            position: absolute;
            text-align: center;
            top: 0;
            width: 102px;
            z-index: 2;
        }

        .grade-sheet-ribbon::after {
            border-left: 51px solid transparent;
            border-right: 51px solid transparent;
            border-top: 28px solid #f2142d;
            bottom: -28px;
            content: "";
            left: 0;
            position: absolute;
        }

        .grade-sheet-header {
            border-bottom: 1px solid #e1e5ef;
            padding: 28px 150px 18px;
        }

        .grade-sheet-header h1 {
            color: #3f445f;
            font-size: 38px;
            font-weight: 700;
            line-height: 1.25;
            margin: 0 0 8px;
        }

        .grade-sheet-header p,
        .grade-sheet-meta p {
            font-size: 19px;
            line-height: 1.7;
            margin: 0;
        }

        .grade-filter {
            background: #f7f8ff;
            border-bottom: 1px solid #e1e5ef;
            padding: 18px 24px 2px;
        }

        .grade-filter label {
            font-weight: 700;
        }

        .grade-sheet-meta {
            display: flex;
            justify-content: space-between;
            padding: 22px 24px 12px;
        }

        .grade-sheet-table-wrap {
            padding: 0 8px 12px;
        }

        .grade-sheet-table {
            border-collapse: collapse;
            min-width: 1220px;
        }

        .grade-sheet-table th {
            background: #d9ddff;
            border: 1px solid #111;
            color: #333952;
            font-size: 16px;
            font-weight: 600;
            line-height: 1.35;
            padding: 12px;
            vertical-align: middle;
        }

        .grade-sheet-table th small {
            color: #59607d;
            font-size: 13px;
        }

        .grade-sheet-table td {
            border: 1px solid #111;
            font-size: 15px;
            line-height: 1.45;
            padding: 10px 12px;
            vertical-align: middle;
        }

        .grade-sheet-table td strong,
        .grade-sheet-table td span {
            display: block;
        }

        .grade-sheet-table td span {
            color: #525a78;
            font-size: 13px;
            margin-top: 2px;
        }

        .grade-input {
            background: #fff;
            border: 1px solid #cfd5f0;
            border-radius: 2px;
            color: #4a506d;
            font-size: 15px;
            min-height: 38px;
        }

        .grade-input[readonly] {
            background: #eef1f7;
        }

        .score-cell {
            width: 110px;
        }

        .grade-check {
            accent-color: #5865f2;
            height: 20px;
            width: 20px;
        }

        .grade-action {
            align-items: center;
            display: inline-flex;
            height: 36px;
            justify-content: center;
            width: 36px;
        }

        .grade-sheet-footer {
            border-top: 1px solid #e1e5ef;
            padding: 14px 24px 20px;
            text-align: right;
        }

        #courseGradeTable_wrapper {
            padding: 0 8px 16px;
        }

        @media (max-width: 768px) {
            .grade-sheet-ribbon {
                height: 78px;
                left: 16px;
                padding-top: 22px;
                width: 72px;
            }

            .grade-sheet-ribbon::after {
                border-left-width: 36px;
                border-right-width: 36px;
                border-top-width: 20px;
                bottom: -20px;
            }

            .grade-sheet-header {
                padding: 24px 16px 16px 104px;
                text-align: left !important;
            }

            .grade-sheet-header h1 {
                font-size: 28px;
            }

            .grade-sheet-header p,
            .grade-sheet-meta p {
                font-size: 16px;
            }

            .grade-sheet-meta {
                display: block;
                padding: 18px 16px 10px;
            }

            .grade-sheet-meta .text-right {
                margin-top: 10px;
                text-align: left !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function() {
            const table = $('#courseGradeTable');

            if (table.length && $.fn.DataTable && ! $.fn.DataTable.isDataTable(table[0])) {
                table.DataTable({
                    responsive: false,
                    autoWidth: false,
                    paging: false,
                    order: [[0, 'asc']],
                    columnDefs: [{ targets: -1, orderable: false, searchable: false }]
                });
            }

            $(document).on('input', '.js-score', function() {
                const input = $(this);
                const score = parseFloat(input.val());
                const passScore = parseFloat(input.data('pass-score')) || 0;
                const row = input.closest('tr');
                const passed = ! Number.isNaN(score) && score >= passScore;

                row.find('.js-result').val(passed ? 'ជាប់' : 'មិនទាន់');
                row.find('.js-passed').prop('checked', passed);
            });
        });
    </script>
@endpush
