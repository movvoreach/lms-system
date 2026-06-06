@extends('admin.layouts.master')

@section('title', 'កែប្រែប្រភេទ Course')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7"><h1>កែប្រែប្រភេទ Course</h1></div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.course-categories.index') }}">ប្រភេទ Course</a></li>
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
        <div class="card-header"><h3 class="card-title mb-0">កែប្រែព័ត៌មានប្រភេទ Course</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.course-categories.update', $courseCategory->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>ឈ្មោះ <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $courseCategory->name) }}" required>
                    @error('name')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>ពិពណ៌នា</label>
                    <textarea name="description" rows="4"
                        class="form-control @error('description') is-invalid @enderror">{{ old('description', $courseCategory->description) }}</textarea>
                    @error('description')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="custom-control-input"
                            id="isActive" @checked(old('is_active', $courseCategory->is_active))>
                        <label class="custom-control-label" for="isActive">សកម្ម</label>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.course-categories.index') }}" class="btn btn-light border mr-2">បោះបង់</a>
                    <button type="submit" class="btn btn-warning">កែប្រែ</button>
                </div>
            </form>
        </div>
    </div>
@endsection
