@extends('admin.layouts.master')

@section('title', 'កែប្រែអ្នកប្រើ')

@section('content')
    <section class="content-header mt-4 px-0">
        <div class="container-fluid px-0">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-7"><h1>កែប្រែអ្នកប្រើ</h1></div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">អ្នកប្រើ</a></li>
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
        <div class="card-header"><h3 class="card-title mb-0">កែប្រែព័ត៌មានអ្នកប្រើ</h3></div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->user_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ឈ្មោះអ្នកប្រើ <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username', $user->username) }}" maxlength="100" required>
                            @error('username')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" maxlength="150" required>
                            @error('email')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Password ថ្មី</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>បញ្ជាក់ Password ថ្មី</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label>តួនាទី <span class="text-danger">*</span></label>
                        <div class="row">
                            @foreach ($roles as $role)
                                <div class="col-md-4">
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" name="roles[]" value="{{ $role->role_id }}"
                                            class="custom-control-input @error('roles') is-invalid @enderror"
                                            id="role{{ $role->role_id }}"
                                            @checked(in_array($role->role_id, old('roles', $selectedRoles)))>
                                        <label class="custom-control-label" for="role{{ $role->role_id }}">
                                            {{ $role->role_name }}
                                        </label>
                                        <small class="d-block text-muted">{{ $role->description }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('roles')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                    </div>

                    <div class="col-md-12 mt-2">
                        <div class="custom-control custom-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="custom-control-input"
                                id="isActive" @checked(old('is_active', $user->is_active))>
                            <label class="custom-control-label" for="isActive">សកម្ម</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light border mr-2">បោះបង់</a>
                    <button type="submit" class="btn btn-warning">កែប្រែ</button>
                </div>
            </form>
        </div>
    </div>
@endsection
