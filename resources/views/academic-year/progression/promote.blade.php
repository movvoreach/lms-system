@extends('admin.layouts.master')

@section('title', 'ផ្ទេរសិស្សឡើងឆ្នាំ')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1 class="mb-1">ផ្ទេរសិស្សឡើងឆ្នាំ</h1>
                    <p class="text-muted mb-0">ការផ្ទេរនឹងបង្កើតកំណត់ត្រាឆ្នាំសិក្សាថ្មី និងមិនប៉ះពាល់ប្រវត្តិចាស់ឡើយ។</p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.academic-progression.index') }}">វឌ្ឍនភាពឆ្នាំសិក្សា</a>
                        </li>
                        <li class="breadcrumb-item active">ផ្ទេរ</li>
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
            <h3 class="card-title mb-0">ច្បាប់ផ្ទេរសិស្ស</h3>
        </div>

        <div class="card-body">

            <!-- Filter Source Year -->
            <form method="GET" action="{{ route('admin.academic-progression.promote') }}" class="mb-4">
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label>ឆ្នាំសិក្សាប្រភព</label>
                        <select name="from_academic_year_id" class="form-control custom-select">
                            <option value="">-- ជ្រើសឆ្នាំប្រភព --</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->academic_year_id }}"
                                    @selected(request('from_academic_year_id') == $year->academic_year_id)>
                                    {{ $year->year_label }} ({{ $year->status }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-info btn-block">
                            ផ្ទុកសិស្ស
                        </button>
                    </div>
                </div>
            </form>

            <!-- Promotion Form -->
            <form action="{{ route('admin.academic-progression.store') }}" method="POST">
                @csrf

                <input type="hidden" name="from_academic_year_id" value="{{ request('from_academic_year_id') }}">

                <div class="row">

                    <div class="col-md-4">
                        <label>ឆ្នាំសិក្សាគោលដៅ <span class="text-danger">*</span></label>

                        <select name="to_academic_year_id"
                            class="form-control custom-select @error('to_academic_year_id') is-invalid @enderror"
                            required>

                            <option value="">-- ជ្រើសឆ្នាំគោលដៅ --</option>

                            @foreach ($academicYears as $year)
                                <option value="{{ $year->academic_year_id }}"
                                    @selected(old('to_academic_year_id') == $year->academic_year_id)>
                                    {{ $year->year_label }} ({{ $year->status }})
                                </option>
                            @endforeach
                        </select>

                        @error('to_academic_year_id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>ប្រភេទផ្ទេរ</label>

                        <select name="promotion_type" class="form-control custom-select">
                            <option value="batch" @selected(old('promotion_type', 'batch') === 'batch')>ផ្ទេរជាក្រុម</option>
                            <option value="manual" @selected(old('promotion_type') === 'manual')>ផ្ទេរដោយដៃ</option>
                            <option value="auto" @selected(old('promotion_type') === 'auto')>ផ្ទេរដោយស្វ័យប្រវត្តិ</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>ស្ថានភាពគោលដៅ</label>

                        <input type="text" name="target_status"
                            class="form-control"
                            value="{{ old('target_status', 'បានផ្ទេរ') }}"
                            maxlength="30">
                    </div>

                    <div class="col-12 mt-3">
                        <label>កំណត់ចំណាំ</label>

                        <textarea name="notes" rows="2" class="form-control">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <hr>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 48px;">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>លេខសម្គាល់សិស្ស</th>
                                <th>ឈ្មោះ</th>
                                <th>មុខវិជ្ជា</th>
                                <th>ស្ថានភាព</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($records as $record)
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            name="student_ids[]"
                                            value="{{ $record->student_id }}"
                                            class="student-check">
                                    </td>

                                    <td>{{ $record->student->student_number ?? 'មិនមាន' }}</td>

                                    <td>
                                        {{ trim(($record->student->first_name ?? '') . ' ' . ($record->student->last_name ?? '')) ?: 'មិនមាន' }}
                                    </td>

                                    <td>{{ $record->course->title ?? 'មិនមាន' }}</td>

                                    <td>{{ $record->status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        សូមជ្រើសឆ្នាំប្រភពដើម្បីបង្ហាញសិស្ស
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.academic-progression.index') }}"
                        class="btn btn-light border mr-2">
                        បោះបង់
                    </a>

                    <button type="submit"
                        class="btn btn-primary"
                        @disabled($records->isEmpty())>
                        ផ្ទេរដែលបានជ្រើស
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('selectAll')?.addEventListener('change', function () {
            document.querySelectorAll('.student-check').forEach((checkbox) => {
                checkbox.checked = this.checked;
            });
        });
    </script>
@endpush
