@extends('admin.layouts.master')

@section('title', 'កែប្រែឆមាស')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1>កែប្រែឆមាស</h1>
                </div>

                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.semesters.index') }}">ឆមាស</a>
                        </li>
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
            <h3 class="card-title mb-0">ព័ត៌មានឆមាស</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.semesters.update', $semester->semester_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ឆ្នាំសិក្សា <span class="text-danger">*</span></label>

                            <select name="academic_year_id"
                                class="form-control custom-select @error('academic_year_id') is-invalid @enderror"
                                required>

                                <option value="">-- ជ្រើសឆ្នាំសិក្សា --</option>

                                @foreach ($academicYears as $academicYear)
                                    <option value="{{ $academicYear->academic_year_id }}"
                                        @selected(old('academic_year_id', $semester->academic_year_id) == $academicYear->academic_year_id)>
                                        {{ $academicYear->year_label }}
                                    </option>
                                @endforeach
                            </select>

                            @error('academic_year_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Study Year <span class="text-danger">*</span></label>
                            <select name="study_year" class="form-control custom-select" required>
                                @foreach ([1, 2, 3, 4] as $year)
                                    <option value="{{ $year }}" @selected((int) old('study_year', $semester->study_year) === $year)>Year {{ $year }}</option>
                                @endforeach
                            </select>
                            @error('study_year')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Semester <span class="text-danger">*</span></label>
                            <select name="term_number" class="form-control custom-select" required>
                                <option value="1" @selected((int) old('term_number', $semester->term_number) === 1)>Semester 1</option>
                                <option value="2" @selected((int) old('term_number', $semester->term_number) === 2)>Semester 2</option>
                            </select>
                            @error('term_number')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ឈ្មោះឆមាស <span class="text-danger">*</span></label>

                            <input type="text" name="semester_name"
                                class="form-control @error('semester_name') is-invalid @enderror"
                                value="{{ old('semester_name', $semester->semester_name) }}"
                                maxlength="50"
                                required>

                            @error('semester_name')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ថ្ងៃចាប់ផ្តើម <span class="text-danger">*</span></label>

                            <input type="date" name="start_date"
                                class="form-control @error('start_date') is-invalid @enderror"
                                value="{{ old('start_date', $semester->start_date?->format('Y-m-d')) }}"
                                required>

                            @error('start_date')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ថ្ងៃបញ្ចប់ <span class="text-danger">*</span></label>

                            <input type="date" name="end_date"
                                class="form-control @error('end_date') is-invalid @enderror"
                                value="{{ old('end_date', $semester->end_date?->format('Y-m-d')) }}"
                                required>

                            @error('end_date')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.semesters.index') }}"
                        class="btn btn-light border mr-2">
                        បោះបង់
                    </a>

                    <button type="submit" class="btn btn-warning">
                        កែប្រែ
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
