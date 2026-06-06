@extends('admin.layouts.master')

@section('title', 'ការកំណត់ Profile | LMS')

@push('styles')
    <style>
        .profile-hero {
            background: linear-gradient(135deg, #1f5fd6, #15a3a3);
            border-radius: 8px;
            color: #fff;
            overflow: hidden;
            padding: 28px;
            position: relative;
        }

        .profile-hero::after {
            background: rgba(255, 255, 255, .12);
            border-radius: 50%;
            content: "";
            height: 220px;
            position: absolute;
            right: -70px;
            top: -90px;
            width: 220px;
        }

        .profile-avatar {
            align-items: center;
            background: rgba(255, 255, 255, .16);
            border: 3px solid rgba(255, 255, 255, .72);
            border-radius: 50%;
            display: inline-flex;
            height: 104px;
            justify-content: center;
            overflow: hidden;
            width: 104px;
        }

        .profile-avatar img {
            height: 100%;
            object-fit: cover;
            width: 100%;
        }

        .profile-card {
            border: 0;
            border-radius: 8px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, .08);
        }

        .setting-panel {
            border: 1px solid #e5eaf2;
            border-radius: 8px;
            padding: 18px;
        }

        .setting-icon {
            align-items: center;
            border-radius: 8px;
            display: inline-flex;
            flex: 0 0 54px;
            font-size: 24px;
            height: 54px;
            justify-content: center;
            width: 54px;
        }

        .setting-icon.enabled {
            background: #d8f3e6;
            color: #0f7a42;
        }

        .setting-icon.disabled {
            background: #fde2e2;
            color: #b42318;
        }

        .detail-row {
            align-items: center;
            border-bottom: 1px solid #edf1f7;
            display: flex;
            justify-content: space-between;
            padding: 14px 0;
        }

        .detail-row:last-child {
            border-bottom: 0;
        }

        .detail-label {
            color: #6b7280;
            font-weight: 700;
        }

        .detail-value {
            color: #111827;
            font-weight: 700;
            text-align: right;
        }

        .role-pill {
            background: #eef4ff;
            border-radius: 999px;
            color: #245bd6;
            display: inline-flex;
            font-size: 13px;
            font-weight: 800;
            margin: 2px;
            padding: 6px 10px;
        }

        .custom-switch .custom-control-label {
            cursor: pointer;
            font-weight: 700;
        }

        @media (max-width: 767px) {
            .detail-row {
                align-items: flex-start;
                flex-direction: column;
                gap: 4px;
            }

            .detail-value {
                text-align: left;
            }
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
    @php
        $avatarUrl = $user->avatar
            ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar))
            : asset('backend/dist/img/user2-160x160.jpg');
    @endphp

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle mr-1"></i>
            {{ $errors->first() }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="profile-hero mb-4">
                <div class="position-relative">
                    <div class="profile-avatar mb-3">
                        <img src="{{ $avatarUrl }}" alt="User">
                    </div>

                    <h3 class="mb-1">{{ $user->username }}</h3>
                    <p class="mb-3 opacity-75">{{ $user->email }}</p>

                    <span class="badge badge-light">
                        <i class="fas fa-circle text-{{ $user->is_active ? 'success' : 'secondary' }} mr-1"></i>
                        {{ $user->is_active ? 'សកម្ម' : 'បិទដំណើរការ' }}
                    </span>
                </div>
            </div>

            <div class="card profile-card mb-4">
                <div class="card-header bg-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-camera mr-2 text-primary"></i>
                        Profile Image
                    </h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label>Upload Image</label>
                            <input type="file"
                                name="avatar"
                                class="form-control-file @error('avatar') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/webp"
                                required>
                            <small class="form-text text-muted">JPG, PNG, or WEBP. Maximum 2MB.</small>
                            @error('avatar')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-upload mr-1"></i>
                            Change Image
                        </button>
                    </form>
                </div>
            </div>

            <div class="card profile-card">
                <div class="card-header bg-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-id-badge mr-2 text-primary"></i>
                        សង្ខេបគណនី
                    </h3>
                </div>
                <div class="card-body">
                    <div class="detail-row">
                        <span class="detail-label">User ID</span>
                        <span class="detail-value">#{{ $user->user_id }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">ចូលប្រើចុងក្រោយ</span>
                        <span class="detail-value">{{ $user->last_login_at?->format('Y-m-d H:i') ?? 'មិនធ្លាប់ចូល' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">2FA</span>
                        <span class="detail-value text-{{ $user->two_factor_enabled ? 'success' : 'danger' }}">
                            {{ $user->two_factor_enabled ? 'បានបើក' : 'បានបិទ' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card profile-card" id="two-factor">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-user-shield mr-2 text-primary"></i>
                        សុវត្ថិភាព 2FA (OTP Email)
                    </h3>
                    <span class="badge badge-{{ $user->two_factor_enabled ? 'success' : 'danger' }}">
                        {{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>

                <div class="card-body">
                    <div class="setting-panel mb-4">
                        <div class="d-flex align-items-start">
                            <div class="setting-icon {{ $user->two_factor_enabled ? 'enabled' : 'disabled' }} mr-3">
                                <i class="fas {{ $user->two_factor_enabled ? 'fa-shield-alt' : 'fa-shield-virus' }}"></i>
                            </div>

                            <div>
                                <h5 class="mb-1">{{ $user->two_factor_enabled ? 'គណនីនេះបានការពារ 2FA' : 'គណនីនេះមិនទាន់បើក 2FA' }}</h5>
                                <p class="text-muted mb-0">
                                    ពេលបើក ប្រព័ន្ធនឹងផ្ញើ OTP 6 ខ្ទង់ទៅអ៊ីមែល
                                    <strong>{{ $user->email }}</strong>
                                    រៀងរាល់ពេល Login។
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('profile.two-factor.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="two_factor_enabled" value="0">

                        <div class="d-flex flex-wrap justify-content-between align-items-center setting-panel">
                            <div class="mb-2 mb-md-0">
                                <h6 class="mb-1">តម្រូវឲ្យមាន OTP ពេល Login</h6>
                                <span class="text-muted">បង្កើនសុវត្ថិភាពគណនី និងការពារការចូលប្រើដោយគ្មានការអនុញ្ញាត។</span>
                            </div>

                            <div class="custom-control custom-switch">
                                <input type="checkbox"
                                    class="custom-control-input"
                                    id="twoFactorEnabled"
                                    name="two_factor_enabled"
                                    value="1"
                                    @checked(old('two_factor_enabled', $user->two_factor_enabled))>

                                <label class="custom-control-label" for="twoFactorEnabled">
                                    {{ $user->two_factor_enabled ? 'បើក' : 'បិទ' }}
                                </label>
                            </div>
                        </div>

                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save mr-1"></i>
                                រក្សាទុកការកំណត់
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card profile-card">
                <div class="card-header bg-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-user-cog mr-2 text-primary"></i>
                        ព័ត៌មានគណនី
                    </h3>
                </div>

                <div class="card-body">
                    <div class="detail-row">
                        <span class="detail-label">ឈ្មោះអ្នកប្រើ</span>
                        <span class="detail-value">{{ $user->username }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">អ៊ីមែល</span>
                        <span class="detail-value">{{ $user->email }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">តួនាទី</span>
                        <span class="detail-value">
                            @forelse($user->roles as $role)
                                <span class="role-pill">{{ $role->role_name }}</span>
                            @empty
                                <span class="text-muted">មិនមានតួនាទី</span>
                            @endforelse
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">ស្ថានភាព</span>
                        <span class="detail-value">
                            <span class="badge badge-{{ $user->is_active ? 'success' : 'secondary' }}">
                                {{ $user->is_active ? 'សកម្ម' : 'បិទដំណើរការ' }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
