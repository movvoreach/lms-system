@extends('admin.layouts.master')

@section('title', 'បង្កើតឆមាស')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1>បង្កើតឆមាស</h1>
                </div>

                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.semesters.index') }}">ឆមាស</a>
                        </li>
                        <li class="breadcrumb-item active">បង្កើត</li>
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
            <form action="{{ route('admin.semesters.store') }}" method="POST">
                @csrf

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
                                        @selected(old('academic_year_id') == $academicYear->academic_year_id)>
                                        {{ $academicYear->year_label }}
                                    </option>
                                @endforeach
                            </select>

                            @error('academic_year_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ឈ្មោះឆមាស <span class="text-danger">*</span></label>

                            <input type="text" name="semester_name"
                                class="form-control @error('semester_name') is-invalid @enderror"
                                placeholder="ឆមាសទី 1"
                                value="{{ old('semester_name') }}"
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
                                value="{{ old('start_date') }}"
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
                                value="{{ old('end_date') }}"
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

                    <button type="submit" class="btn btn-primary">
                        រក្សាទុក
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
