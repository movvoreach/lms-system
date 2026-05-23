<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Username <span class="text-danger">*</span></label>
            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                value="{{ old('username', $student->user->username ?? '') }}" maxlength="100" required>
            @error('username')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $student->user->email ?? '') }}" maxlength="150" required>
            @error('email')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Password @empty($student)<span class="text-danger">*</span>@endempty</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                @empty($student) required @endempty>
            @isset($student)
                <small class="form-text text-muted">Leave blank to keep current password.</small>
            @endisset
            @error('password')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Confirm Password @empty($student)<span class="text-danger">*</span>@endempty</label>
            <input type="password" name="password_confirmation" class="form-control" @empty($student) required @endempty>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Course</label>
            <select name="course_id" class="form-control custom-select @error('course_id') is-invalid @enderror">
                <option value="">-- Select course --</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id', $student->course_id ?? '') == $course->id)>
                        {{ $course->title }}
                    </option>
                @endforeach
            </select>
            @error('course_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Initial Academic Year</label>
            <select name="academic_year_id" class="form-control custom-select @error('academic_year_id') is-invalid @enderror"
                @isset($student) disabled @endisset>
                <option value="">-- Select academic year --</option>
                @foreach ($academicYears as $academicYear)
                    <option value="{{ $academicYear->academic_year_id }}" @selected(old('academic_year_id') == $academicYear->academic_year_id)>
                        {{ $academicYear->year_label }} ({{ $academicYear->status }})
                    </option>
                @endforeach
            </select>
            @isset($student)
                <small class="form-text text-muted">Academic year history is managed from Academic Progression.</small>
            @endisset
            @error('academic_year_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Student Number <span class="text-danger">*</span></label>
            <input type="text" name="student_number" class="form-control @error('student_number') is-invalid @enderror"
                value="{{ old('student_number', $student->student_number ?? '') }}" maxlength="50" required>
            @error('student_number')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Status</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror"
                value="{{ old('status', $student->status ?? 'active') }}" maxlength="30">
            @error('status')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                value="{{ old('first_name', $student->first_name ?? '') }}" maxlength="100">
            @error('first_name')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                value="{{ old('last_name', $student->last_name ?? '') }}" maxlength="100">
            @error('last_name')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Gender</label>
            <input type="text" name="gender" class="form-control @error('gender') is-invalid @enderror"
                value="{{ old('gender', $student->gender ?? '') }}" maxlength="20">
            @error('gender')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
                value="{{ old('date_of_birth', isset($student) ? $student->date_of_birth?->format('Y-m-d') : '') }}">
            @error('date_of_birth')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone', $student->phone ?? '') }}" maxlength="30">
            @error('phone')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label>Address</label>
            <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $student->address ?? '') }}</textarea>
            @error('address')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>
</div>
