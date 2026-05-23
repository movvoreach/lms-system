@extends('admin.layouts.master')

@section('title', 'Profile Settings | LMS')

@push('styles')
    <style>
        .security-state {
            border: 1px solid #e7e9ef;
            border-radius: 8px;
            padding: 18px;
            background: #fff;
        }

        .security-icon {
            width: 52px;
            height: 52px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 24px;
        }

        .security-icon.enabled {
            color: #0f5132;
            background: #d1e7dd;
        }

        .security-icon.disabled {
            color: #842029;
            background: #f8d7da;
        }

        .custom-switch .custom-control-label {
            cursor: pointer;
            font-weight: 600;
        }
    </style>
@endpush

@section('page-title')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Profile Settings</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-1"></i>
            {{ $errors->first() }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ asset('backend/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2 mb-3"
                        width="96" height="96" alt="User avatar">
                    <h4 class="mb-1">{{ $user->username }}</h4>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    <span class="badge badge-{{ $user->is_active ? 'success' : 'secondary' }}">
                        {{ $user->is_active ? 'Active' : 'Disabled' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card" id="two-factor">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-user-shield mr-2"></i>
                        Two-Factor Authentication
                    </h3>
                </div>

                <div class="card-body">
                    <div class="security-state mb-4">
                        <div class="d-flex align-items-center">
                            <div class="security-icon {{ $user->two_factor_enabled ? 'enabled' : 'disabled' }} mr-3">
                                <i class="fas {{ $user->two_factor_enabled ? 'fa-shield-alt' : 'fa-shield-virus' }}"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">
                                    Email OTP is {{ $user->two_factor_enabled ? 'enabled' : 'disabled' }}
                                </h5>
                                <p class="text-muted mb-0">
                                    When enabled, each login requires a 6-digit code sent to {{ $user->email }}.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('profile.two-factor.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="two_factor_enabled" value="0">

                        <div class="custom-control custom-switch mb-4">
                            <input type="checkbox" class="custom-control-input" id="twoFactorEnabled"
                                name="two_factor_enabled" value="1" @checked(old('two_factor_enabled', $user->two_factor_enabled))>
                            <label class="custom-control-label" for="twoFactorEnabled">
                                Require email OTP after password login
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>
                            Save Security Settings
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Account Details</h3>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Username</dt>
                        <dd class="col-sm-8">{{ $user->username }}</dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $user->email }}</dd>

                        <dt class="col-sm-4">Roles</dt>
                        <dd class="col-sm-8">{{ $user->roles->pluck('role_name')->join(', ') ?: 'No roles assigned' }}</dd>

                        <dt class="col-sm-4">Last login</dt>
                        <dd class="col-sm-8">{{ $user->last_login_at?->format('Y-m-d H:i') ?? 'Never' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
