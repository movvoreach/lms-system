@extends('admin.layouts.master')

@section('title', 'Edit Announcement')

@section('content')
    <section class="content-header mt-4 px-0">
        <h1>Edit Announcement</h1>
    </section>

    <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" enctype="multipart/form-data">
        @method('PUT')
        <div class="card shadow-sm">
            <div class="card-body">
                @include('announcement._form')
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-light border">Back</a>
                <button type="submit" class="btn btn-primary">Update Announcement</button>
            </div>
        </div>
    </form>
@endsection
