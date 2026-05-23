<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Username <span class="text-danger">*</span></label>
            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                value="{{ old('username', $teacher->user->username ?? '') }}" maxlength="100" required>
            @error('username')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $teacher->user->email ?? '') }}" maxlength="150" required>
            @error('email')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Password @empty($teacher)<span class="text-danger">*</span>@endempty</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                @empty($teacher) required @endempty>
            @isset($teacher)
                <small class="form-text text-muted">Leave blank to keep current password.</small>
            @endisset
            @error('password')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Confirm Password @empty($teacher)<span class="text-danger">*</span>@endempty</label>
            <input type="password" name="password_confirmation" class="form-control" @empty($teacher) required @endempty>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Course</label>
            <select name="course_id" class="form-control custom-select @error('course_id') is-invalid @enderror">
                <option value="">-- Select course --</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id', $teacher->course_id ?? '') == $course->id)>
                        {{ $course->title }}
                    </option>
                @endforeach
            </select>
            @error('course_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Employee Number <span class="text-danger">*</span></label>
            <input type="text" name="employee_number" class="form-control @error('employee_number') is-invalid @enderror"
                value="{{ old('employee_number', $teacher->employee_number ?? '') }}" maxlength="50" required>
            @error('employee_number')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Status</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror"
                value="{{ old('status', $teacher->status ?? 'active') }}" maxlength="30">
            @error('status')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                value="{{ old('first_name', $teacher->first_name ?? '') }}" maxlength="100">
            @error('first_name')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                value="{{ old('last_name', $teacher->last_name ?? '') }}" maxlength="100">
            @error('last_name')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Specialization</label>
            <input type="text" name="specialization" class="form-control @error('specialization') is-invalid @enderror"
                value="{{ old('specialization', $teacher->specialization ?? '') }}" maxlength="200">
            @error('specialization')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Academic Rank</label>
            <input type="text" name="academic_rank" class="form-control @error('academic_rank') is-invalid @enderror"
                value="{{ old('academic_rank', $teacher->academic_rank ?? '') }}" maxlength="100">
            @error('academic_rank')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone', $teacher->phone ?? '') }}" maxlength="30">
            @error('phone')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label>Address</label>
            <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $teacher->address ?? '') }}</textarea>
            @error('address')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>
</div>
