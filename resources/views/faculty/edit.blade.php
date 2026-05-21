@extends('admin.layouts.master')

@section('title', 'កែប្រែ មហាវិទ្យាល័យ')

@section('content')
<section class="content-header mt-4 px-0">
    <div class="container-fluid px-0">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-7">
                <h1>កែប្រែ មហាវិទ្យាល័យ</h1>
            </div>
            <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">ផ្ទាំងគ្រប់គ្រង</a></li>
                    <li class="breadcrumb-item"><a href="#">មហាវិទ្យាល័យ</a></li>
                    <li class="breadcrumb-item active">កែប្រែ</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title mb-0">

            កែប្រែព័ត៌មានមហាវិទ្យាល័យ
        </h3>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.faculty.update', $faculty->faculty_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">

                {{-- Faculty Code --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>កូដមហាវិទ្យាល័យ <span class="text-danger">*</span></label>

                        <input type="text"
                               name="faculty_code"
                               class="form-control custom-input @error('faculty_code') is-invalid @enderror"
                               value="{{ old('faculty_code', $faculty->faculty_code) }}"
                               maxlength="30"
                               required>

                        @error('faculty_code')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Faculty Name --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>ឈ្មោះមហាវិទ្យាល័យ <span class="text-danger">*</span></label>

                        <input type="text"
                               name="faculty_name"
                               class="form-control custom-input @error('faculty_name') is-invalid @enderror"
                               value="{{ old('faculty_name', $faculty->faculty_name) }}"
                               maxlength="150"
                               required>

                        @error('faculty_name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>

            {{-- Actions --}}
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.faculty.index') }}"
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
