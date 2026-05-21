@extends('admin.layouts.master')

@section('title', 'User Profile')

@push('styles')
    <style>
        .card-title {
            font-weight: 600;
        }

        .profile-image {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #ddd;
            background: #f8f9fa;
        }

        .info-label {
            font-weight: 600;
            color: #333;
        }

        .info-value {
            color: #555;
        }

        .profile-box {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            background: #fff;
            margin-bottom: 15px;
        }
    </style>
@endpush

@section('content')

    <div class="row mt-5">
        <div class="col-12">
            <div class="page-header mt-2">
                <h2 class="pageheader-title">User Profile</h2>
                <hr>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/dashboard">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('teacher.index') }}">Teacher List</a>
                        </li>
                        <li class="breadcrumb-item active">
                            User Profile
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center">
            <h3 class="card-title mb-0">Profile Information</h3>
            <a href="{{ route('teacher.index') }}" class="btn btn-secondary btn-sm ml-auto">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="card-body">
            <div class="row">

                {{-- Profile Image --}}
                <div class="col-md-4 text-center mb-4">
                    @if(!empty($user->teacher->profile_image))
                        <img src="{{ asset('storage/' . $user->teacher->profile_image) }}" alt="Profile Image" class="profile-image">
                    @else
                        <img src="https://via.placeholder.com/140x140.png?text=No+Image" alt="No Image" class="profile-image">
                    @endif

                    <div class="mt-3">
                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-0">Teacher</p>
                    </div>
                </div>

                {{-- Profile Details --}}
                <div class="col-md-8">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="profile-box">
                                <div class="info-label">Teacher ID</div>
                                <div class="info-value">{{ $user->teacher->teacher_id ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="profile-box">
                                <div class="info-label">Full Name</div>
                                <div class="info-value">{{ $user->name ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="profile-box">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $user->email ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="profile-box">
                                <div class="info-label">Course</div>
                                <div class="info-value">{{ $user->teacher->course ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="profile-box">
                                <div class="info-label">Experience</div>
                                <div class="info-value">{{ $user->teacher->experience ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="profile-box">
                                <div class="info-label">Education</div>
                                <div class="info-value">{{ $user->teacher->education ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="profile-box">
                                <div class="info-label">Skills</div>
                                <div class="info-value">{{ $user->teacher->skills ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="profile-box">
                                <div class="info-label">Contact</div>
                                <div class="info-value">{{ $user->teacher->contact ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="profile-box">
                                <div class="info-label">Note</div>
                                <div class="info-value">{{ $user->teacher->note ?? '-' }}</div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer text-center">
            <a href="{{ route('teacher.edit', $user->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Profile
            </a>

            <a href="{{ route('teacher.index') }}" class="btn btn-danger">
                Cancel
            </a>
        </div>
    </div>

@endsection
