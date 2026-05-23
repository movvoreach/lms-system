@extends('admin.layouts.master')

@section('title', 'កែប្រែ Course')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7"><h1>កែប្រែ Course</h1></div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Course</a></li>
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
        <div class="card-header"><h3 class="card-title mb-0">កែប្រែព័ត៌មាន Course</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.courses.update', $course->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ប្រភេទ <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control custom-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- ជ្រើសរើសប្រភេទ --</option>
                                @foreach ($courseCategories as $courseCategory)
                                    <option value="{{ $courseCategory->id }}" @selected(old('category_id', $course->category_id) == $courseCategory->id)>
                                        {{ $courseCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ដេប៉ាតឺម៉ង់ <span class="text-danger">*</span></label>
                            <select name="department_id" class="form-control custom-select @error('department_id') is-invalid @enderror" required>
                                <option value="">-- ជ្រើសរើសដេប៉ាតឺម៉ង់ --</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->department_id }}" @selected(old('department_id', $course->department_id) == $department->department_id)>
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Semester</label>
                            <select name="semester_id" class="form-control custom-select @error('semester_id') is-invalid @enderror">
                                <option value="">-- Select semester --</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->semester_id }}" @selected(old('semester_id', $course->semester_id) == $semester->semester_id)>
                                        {{ $semester->semester_name }} - {{ $semester->academicYear->year_label ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('semester_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ចំណងជើង <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $course->title) }}" required>
                            @error('title')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                value="{{ old('code', $course->code) }}">
                            @error('code')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>រយៈពេលម៉ោង</label>
                            <input type="number" name="duration_hours" min="0"
                                class="form-control @error('duration_hours') is-invalid @enderror"
                                value="{{ old('duration_hours', $course->duration_hours) }}">
                            @error('duration_hours')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>តម្លៃ <span class="text-danger">*</span></label>
                            <input type="number" name="fee" min="0" step="0.01"
                                class="form-control @error('fee') is-invalid @enderror"
                                value="{{ old('fee', $course->fee) }}" required>
                            @error('fee')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group pt-md-4 mt-md-2">
                            <div class="custom-control custom-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" class="custom-control-input"
                                    id="isActive" @checked(old('is_active', $course->is_active))>
                                <label class="custom-control-label" for="isActive">សកម្ម</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ថ្ងៃចាប់ផ្តើម</label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                value="{{ old('start_date', $course->start_date?->format('Y-m-d')) }}">
                            @error('start_date')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ថ្ងៃបញ្ចប់</label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                value="{{ old('end_date', $course->end_date?->format('Y-m-d')) }}">
                            @error('end_date')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>ពិពណ៌នា</label>
                            <textarea name="description" rows="4"
                                class="form-control @error('description') is-invalid @enderror">{{ old('description', $course->description) }}</textarea>
                            @error('description')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.courses.index') }}" class="btn btn-light border mr-2">បោះបង់</a>
                    <button type="submit" class="btn btn-warning">កែប្រែ</button>
                </div>
            </form>
        </div>
    </div>
@endsection
