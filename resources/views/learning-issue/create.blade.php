@extends('admin.layouts.master')

@section('title', 'Report Learning Issue')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <h1 class="mb-1">Report Learning Issue</h1>
            <p class="text-muted mb-0">Tell your teacher what is blocking your learning progress.</p>
        </div>
    </section>

    <form method="POST" action="{{ route('admin.learning-issues.store') }}">
        @csrf
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Course</label>
                        <select name="course_id" class="form-control @error('course_id') is-invalid @enderror">
                            <option value="">General / Not course specific</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->title }}</option>
                            @endforeach
                        </select>
                        @error('course_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Issue Type</label>
                        <select name="issue_type" class="form-control @error('issue_type') is-invalid @enderror">
                            @foreach ($issueTypes as $key => $label)
                                <option value="{{ $key }}" @selected(old('issue_type') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('issue_type')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Priority</label>
                        <select name="priority" class="form-control @error('priority') is-invalid @enderror">
                            @foreach ($priorities as $key => $label)
                                <option value="{{ $key }}" @selected(old('priority', 'normal') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('priority')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-8 form-group">
                        <label>Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror">
                        @error('title')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Deadline</label>
                        <input type="datetime-local" name="deadline_at" value="{{ old('deadline_at') }}" class="form-control @error('deadline_at') is-invalid @enderror">
                        @error('deadline_at')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-12 form-group">
                        <label>Description</label>
                        <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('admin.learning-issues.index') }}" class="btn btn-light border">Back</a>
                <button type="submit" class="btn btn-primary">Submit Report</button>
            </div>
        </div>
    </form>
@endsection
