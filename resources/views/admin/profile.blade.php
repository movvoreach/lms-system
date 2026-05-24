@extends('admin.layouts.master')

@section('title', 'ការកំណត់ Profile | LMS')

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
            <h1>ការកំណត់ Profile</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')

    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle mr-1"></i>
            {{ $errors->first() }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">

        {{-- PROFILE CARD --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <img src="{{ asset('backend/dist/img/user2-160x160.jpg') }}"
                        class="img-circle elevation-2 mb-3"
                        width="96" height="96" alt="User">

                    <h4 class="mb-1">{{ $user->username }}</h4>
                    <p class="text-muted mb-2">{{ $user->email }}</p>

                    <span class="badge badge-{{ $user->is_active ? 'success' : 'secondary' }}">
                        {{ $user->is_active ? 'សកម្ម' : 'បិទដំណើរការ' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- SECURITY SETTINGS --}}
        <div class="col-lg-8">

            <div class="card" id="two-factor">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-user-shield mr-2"></i>
                        សុវត្ថិភាព 2FA (OTP Email)
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
                                    {{ $user->two_factor_enabled ? 'បានបើក 2FA' : 'បានបិទ 2FA' }}
                                </h5>

                                <p class="text-muted mb-0">
                                    ពេលបើក នឹងត្រូវបញ្ចូល OTP 6 ខ្ទង់ ដែលផ្ញើទៅអ៊ីមែល {{ $user->email }} ពេល Login។
                                </p>
                            </div>

                        </div>
                    </div>

                    <form action="{{ route('profile.two-factor.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="two_factor_enabled" value="0">

                        <div class="custom-control custom-switch mb-4">
                            <input type="checkbox"
                                class="custom-control-input"
                                id="twoFactorEnabled"
                                name="two_factor_enabled"
                                value="1"
                                @checked(old('two_factor_enabled', $user->two_factor_enabled))>

                            <label class="custom-control-label" for="twoFactorEnabled">
                                តម្រូវឲ្យមាន OTP ពេល Login
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>
                            រក្សាទុកការកំណត់
                        </button>
                    </form>
                </div>
            </div>

            {{-- ACCOUNT DETAILS --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">ព័ត៌មានគណនី</h3>
                </div>

                <div class="card-body">
                    <dl class="row mb-0">

                        <dt class="col-sm-4">ឈ្មោះអ្នកប្រើ</dt>
                        <dd class="col-sm-8">{{ $user->username }}</dd>

                        <dt class="col-sm-4">អ៊ីមែល</dt>
                        <dd class="col-sm-8">{{ $user->email }}</dd>

                        <dt class="col-sm-4">តួនាទី</dt>
                        <dd class="col-sm-8">
                            {{ $user->roles->pluck('role_name')->join(', ') ?: 'មិនមានតួនាទី' }}
                        </dd>

                        <dt class="col-sm-4">ចូលប្រើចុងក្រោយ</dt>
                        <dd class="col-sm-8">
                            {{ $user->last_login_at?->format('Y-m-d H:i') ?? 'មិនធ្លាប់ចូល' }}
                        </dd>

                    </dl>
                </div>
            </div>

        </div>
    </div>
@endsection
