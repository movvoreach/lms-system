@extends('admin.layouts.master')

@section('title', 'បង្កើតគ្រូបង្រៀន')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7"><h1>បង្កើតគ្រូបង្រៀន</h1></div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.teachers.index') }}">គ្រូបង្រៀន</a></li>
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
            <h3 class="card-title mb-0">ព័ត៌មានគ្រូបង្រៀន</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.teachers.store') }}" method="POST">
                @csrf

                @include('teacher._form')

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-light border mr-2">
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
