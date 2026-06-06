@extends('admin.layouts.master')

@section('title', 'កែប្រែមាតិកាមេរៀន')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7">
                    <h1>កែប្រែមាតិកាមេរៀន</h1>
                    <p class="text-muted mb-0">{{ $lessonContent->title }}</p>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.lesson-contents.index') }}">មាតិកាមេរៀន</a></li>
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
            <h3 class="card-title mb-0">កែប្រែព័ត៌មានមាតិកាមេរៀន</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.lesson-contents.update', $lessonContent->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('lesson-content._form', ['lessonContent' => $lessonContent])

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.lesson-contents.index') }}" class="btn btn-light border mr-2">បោះបង់</a>
                    <button type="submit" class="btn btn-warning">កែប្រែ</button>
                </div>
            </form>
        </div>
    </div>
@endsection
