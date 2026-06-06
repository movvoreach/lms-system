@extends('admin.layouts.master')

@section('title', $announcement->title)

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>{{ $announcement->title }}</h1>
                <p class="text-muted mb-0">
                    By {{ $announcement->creator->username ?? 'N/A' }}
                    · {{ $announcement->created_at->format('Y-m-d H:i') }}
                </p>
            </div>
            <a href="{{ route('admin.announcements.index') }}" class="btn btn-light border">Back</a>
        </div>
    </section>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-3">
                @include('announcement.partials.badge', ['type' => 'priority', 'value' => $announcement->priority])
                @include('announcement.partials.badge', ['type' => 'status', 'value' => $announcement->status])
                <span class="badge badge-light border">{{ ucfirst($announcement->target_type) }}</span>
            </div>
            <p style="white-space: pre-line;">{{ $announcement->message }}</p>
            @if ($announcement->attachment_url)
                <hr>
                <a href="{{ $announcement->attachment_url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-paperclip"></i> Download Attachment
                </a>
            @endif
        </div>
    </div>
@endsection
