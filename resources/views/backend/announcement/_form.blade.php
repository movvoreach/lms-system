@csrf

<div class="row">
    <div class="col-md-8 form-group">
        <label>Title / ចំណងជើង</label>
        <input type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" class="form-control @error('title') is-invalid @enderror">
        @error('title')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
    <div class="col-md-4 form-group">
        <label>Status</label>
        <select name="status" class="form-control">
            @foreach ($statuses as $key => $label)
                <option value="{{ $key }}" @selected(old('status', $announcement->status ?? 'draft') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 form-group">
        <label>Target</label>
        <select name="target_type" id="targetType" class="form-control">
            @foreach ($targets as $key => $label)
                <option value="{{ $key }}" @selected(old('target_type', $announcement->target_type ?? 'all') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 form-group">
        <label>Course/Class</label>
        <select name="course_id" id="courseSelect" class="form-control">
            <option value="">None</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected(old('course_id', $announcement->course_id ?? '') == $course->id)>{{ $course->title }}</option>
            @endforeach
        </select>
        @error('course_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
    <div class="col-md-4 form-group">
        <label>Priority</label>
        <select name="priority" class="form-control">
            @foreach ($priorities as $key => $label)
                <option value="{{ $key }}" @selected(old('priority', $announcement->priority ?? 'normal') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 form-group">
        <label>Publish Date</label>
        <input type="datetime-local" name="publish_at" value="{{ old('publish_at', isset($announcement) && $announcement->publish_at ? $announcement->publish_at->format('Y-m-d\\TH:i') : '') }}" class="form-control">
    </div>
    <div class="col-md-6 form-group">
        <label>Expire Date</label>
        <input type="datetime-local" name="expire_at" value="{{ old('expire_at', isset($announcement) && $announcement->expire_at ? $announcement->expire_at->format('Y-m-d\\TH:i') : '') }}" class="form-control">
        @error('expire_at')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
    <div class="col-12 form-group">
        <label>Message / សារ</label>
        <textarea name="message" rows="8" class="form-control @error('message') is-invalid @enderror">{{ old('message', $announcement->message ?? '') }}</textarea>
        @error('message')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
    <div class="col-12 form-group">
        <label>Attachment/File</label>
        <input type="file" name="attachment" class="form-control-file">
        @if (! empty($announcement?->attachment_path))
            <small class="d-block mt-2"><a href="{{ $announcement->attachment_url }}" target="_blank">Current attachment</a></small>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        $(function () {
            function syncCourseField() {
                $('#courseSelect').prop('disabled', $('#targetType').val() !== 'course');
            }
            $('#targetType').on('change', syncCourseField);
            syncCourseField();
        });
    </script>
@endpush
