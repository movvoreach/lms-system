@extends('admin.layouts.master')

@section('title', 'កែប្រែឆ្នាំសិក្សា')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1>កែប្រែឆ្នាំសិក្សា</h1>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.academic-years.index') }}">ឆ្នាំសិក្សា</a>
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
            <h3 class="card-title mb-0">ព័ត៌មានឆ្នាំសិក្សា</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.academic-years.update', $academicYear->academic_year_id) }}"
                method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ឈ្មោះឆ្នាំសិក្សា <span class="text-danger">*</span></label>

                            <input type="text" name="year_label"
                                class="form-control @error('year_label') is-invalid @enderror"
                                value="{{ old('year_label', $academicYear->year_label) }}"
                                maxlength="20"
                                required>

                            @error('year_label')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ថ្ងៃចាប់ផ្តើម <span class="text-danger">*</span></label>

                            <input type="date" name="start_date"
                                class="form-control @error('start_date') is-invalid @enderror"
                                value="{{ old('start_date', $academicYear->start_date?->format('Y-m-d')) }}"
                                required>

                            @error('start_date')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ថ្ងៃបញ្ចប់ <span class="text-danger">*</span></label>

                            <input type="date" name="end_date"
                                class="form-control @error('end_date') is-invalid @enderror"
                                value="{{ old('end_date', $academicYear->end_date?->format('Y-m-d')) }}"
                                required>

                            @error('end_date')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ស្ថានភាព <span class="text-danger">*</span></label>

                            <select name="status"
                                class="form-control custom-select @error('status') is-invalid @enderror"
                                required>

                                @foreach ([
                                    'active' => 'កំពុងដំណើរការ',
                                    'closed' => 'បានបិទ',
                                    'archived' => 'រក្សាទុក'
                                ] as $value => $label)

                                    <option value="{{ $value }}"
                                        @selected(old('status', $academicYear->status) === $value)>
                                        {{ $label }}
                                    </option>

                                @endforeach
                            </select>

                            @error('status')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.academic-years.index') }}"
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
