@extends('admin.layouts.master')

@section('title', 'បង្កើត ដេប៉ាតឺម៉ង់')

@section('content')
<section class="content-header mt-4 px-0">
    <div class="container-fluid px-0">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-7">
                <h1>
                    <i class="fas fa-plus-circle text-primary mr-2"></i>
                    បង្កើត ដេប៉ាតឺម៉ង់
                </h1>
            </div>
            <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">ផ្ទាំងគ្រប់គ្រង</a></li>
                    <li class="breadcrumb-item"><a href="#">ដេប៉ាតឺម៉ង់</a></li>
                    <li class="breadcrumb-item active">បង្កើត</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title mb-0">
            <i class="fas fa-building mr-1"></i>
            ព័ត៌មានដេប៉ាតឺម៉ង់
        </h3>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.departments.store') }}" method="POST">
            @csrf

            <div class="row">

                {{-- Faculty --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>
                            មហាវិទ្យាល័យ <span class="text-danger">*</span>
                        </label>

                        <select name="faculty_id"
                                class="form-control custom-select @error('faculty_id') is-invalid @enderror"
                                required>
                            <option value="">-- ជ្រើសរើសមហាវិទ្យាល័យ --</option>
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->faculty_id }}"
                                    @selected(old('faculty_id') == $faculty->faculty_id)>
                                    {{ $faculty->faculty_name }} ({{ $faculty->faculty_code }})
                                </option>
                            @endforeach
                        </select>

                        @error('faculty_id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Department Code --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>
                            កូដដេប៉ាតឺម៉ង់ <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="department_code"
                               class="form-control custom-input @error('department_code') is-invalid @enderror"
                               placeholder="DEP001"
                               value="{{ old('department_code') }}"
                               maxlength="30"
                               required>

                        @error('department_code')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Department Name --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>
                            ឈ្មោះដេប៉ាតឺម៉ង់ <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="department_name"
                               class="form-control custom-input @error('department_name') is-invalid @enderror"
                               placeholder="Information Technology"
                               value="{{ old('department_name') }}"
                               maxlength="150"
                               required>

                        @error('department_name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Dean --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>
                            ព្រឹទ្ធបុរស
                        </label>

                        <input type="text"
                               name="deans"
                               class="form-control custom-input @error('deans') is-invalid @enderror"
                               placeholder="Dr. John Doe"
                               value="{{ old('deans') }}"
                               maxlength="255">

                        @error('deans')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>

            {{-- Actions --}}
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.departments.index') }}"
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
