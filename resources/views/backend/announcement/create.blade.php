@extends('admin.layouts.master')

@section('title', 'Create Announcement')

@section('content')
    <section class="content-header mt-4 px-0">
        <h1>Create Announcement</h1>
    </section>

    <form method="POST" action="{{ route('admin.announcements.store') }}" enctype="multipart/form-data">
        <div class="card shadow-sm">
            <div class="card-body">
                @include('announcement._form')
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-light border">Back</a>
                <button type="submit" class="btn btn-primary">Save Announcement</button>
            </div>
        </div>
    </form>
@endsection
