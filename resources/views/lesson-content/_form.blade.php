@php
    $lessonContent = $lessonContent ?? null;
    $defaultContentType = $defaultContentType ?? 'lesson';
    $metadataValue = old('metadata', $lessonContent?->metadata ? json_encode($lessonContent->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '');
@endphp

<div class="row">

    <div class="col-md-6">
        <div class="form-group">
            <label>វគ្គសិក្សា <span class="text-danger">*</span></label>
            <select name="course_id" class="form-control custom-select @error('course_id') is-invalid @enderror" required>
                <option value="">-- ជ្រើសរើសវគ្គសិក្សា --</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id', $lessonContent->course_id ?? '') == $course->id)>
                        {{ $course->code ? $course->code . ' - ' : '' }}{{ $course->title }}
                    </option>
                @endforeach
            </select>
            @error('course_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>លេខម៉ូឌុល <span class="text-danger">*</span></label>
            <input type="number" name="module_number" min="1" class="form-control @error('module_number') is-invalid @enderror"
                value="{{ old('module_number', $lessonContent->module_number ?? 1) }}" required>
            @error('module_number')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label>ទីតាំង <span class="text-danger">*</span></label>
            <input type="number" name="position" min="1" class="form-control @error('position') is-invalid @enderror"
                value="{{ old('position', $lessonContent->position ?? 1) }}" required>
            @error('position')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>ចំណងជើងម៉ូឌុល</label>
            <input type="text" name="module_title" class="form-control @error('module_title') is-invalid @enderror"
                value="{{ old('module_title', $lessonContent->module_title ?? 'ម៉ូឌុល ១') }}">
            @error('module_title')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>ចំណងជើងមេរៀន <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $lessonContent->title ?? '') }}" required>
            @error('title')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Slug (តំណខ្លី)</label>
            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                value="{{ old('slug', $lessonContent->slug ?? '') }}" placeholder="បង្កើតស្វ័យប្រវត្តិ បើទទេ">
            @error('slug')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>ប្រភេទមាតិកា <span class="text-danger">*</span></label>
            <select name="content_type" class="form-control custom-select @error('content_type') is-invalid @enderror" required>
                @foreach ($contentTypes as $value => $label)
                    <option value="{{ $value }}" @selected(old('content_type', $lessonContent->content_type ?? $defaultContentType) === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('content_type')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>ការបង្ហាញ <span class="text-danger">*</span></label>
            <select name="visibility" class="form-control custom-select @error('visibility') is-invalid @enderror" required>
                @foreach ($visibilityOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('visibility', $lessonContent->visibility ?? 'visible') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('visibility')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-12" id="group-summary">
        <div class="form-group">
            <label>សង្ខេប</label>
            <textarea name="summary" rows="2" class="form-control @error('summary') is-invalid @enderror">{{ old('summary', $lessonContent->summary ?? '') }}</textarea>
            @error('summary')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-12" id="group-body">
        <div class="form-group">
            <label>ខ្លឹមសារ</label>
            <textarea name="body" rows="8" class="form-control @error('body') is-invalid @enderror">{{ old('body', $lessonContent->body ?? '') }}</textarea>
            @error('body')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-4" id="group-external-url">
        <div class="form-group">
            <label>URL ខាងក្រៅ</label>
            <input type="url" name="external_url" class="form-control @error('external_url') is-invalid @enderror"
                value="{{ old('external_url', $lessonContent->external_url ?? '') }}">
            @error('external_url')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-4" id="group-video-url">
        <div class="form-group">
            <label>URL វីដេអូ</label>
            <input type="url" name="video_url" class="form-control @error('video_url') is-invalid @enderror"
                value="{{ old('video_url', $lessonContent->video_url ?? '') }}">
            @error('video_url')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-4" id="group-file-path">
        <div class="form-group">
            <label>ផ្លូវឯកសារ</label>
            <input type="text" name="file_path" class="form-control @error('file_path') is-invalid @enderror"
                value="{{ old('file_path', $lessonContent->file_path ?? '') }}" placeholder="storage/lessons/file.pdf">
            @error('file_path')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-4" id="group-duration">
        <div class="form-group">
            <label>រយៈពេល (នាទី)</label>
            <input type="number" name="duration_minutes" min="0" class="form-control @error('duration_minutes') is-invalid @enderror"
                value="{{ old('duration_minutes', $lessonContent->duration_minutes ?? '') }}">
            @error('duration_minutes')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-4" id="group-max-score">
        <div class="form-group">
            <label>ពិន្ទុអតិបរមា</label>
            <input type="number" name="max_score" min="0" step="0.01" class="form-control @error('max_score') is-invalid @enderror"
                value="{{ old('max_score', $lessonContent->max_score ?? '') }}">
            @error('max_score')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-4" id="group-passing-score">
        <div class="form-group">
            <label>ពិន្ទុឆ្លង</label>
            <input type="number" name="passing_score" min="0" step="0.01" class="form-control @error('passing_score') is-invalid @enderror"
                value="{{ old('passing_score', $lessonContent->passing_score ?? '') }}">
            @error('passing_score')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>ចាប់ផ្តើមពី</label>
            <input type="datetime-local" name="available_from" class="form-control @error('available_from') is-invalid @enderror"
                value="{{ old('available_from', $lessonContent?->available_from?->format('Y-m-d\TH:i') ?? '') }}">
            @error('available_from')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>ដល់ថ្ងៃ</label>
            <input type="datetime-local" name="available_until" class="form-control @error('available_until') is-invalid @enderror"
                value="{{ old('available_until', $lessonContent?->available_until?->format('Y-m-d\TH:i') ?? '') }}">
            @error('available_until')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label>Metadata JSON (ទិន្នន័យបន្ថែម)</label>
            <textarea name="metadata" rows="4" class="form-control @error('metadata') is-invalid @enderror" placeholder='{"moodle_activity_id": 1}'>{{ $metadataValue }}</textarea>
            @error('metadata')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="custom-control custom-switch mb-3">
            <input type="hidden" name="completion_required" value="0">
            <input type="checkbox" name="completion_required" value="1" class="custom-control-input" id="completionRequired"
                @checked(old('completion_required', $lessonContent->completion_required ?? false))>
            <label class="custom-control-label" for="completionRequired">តម្រូវការបញ្ចប់</label>
        </div>
    </div>

    <div class="col-md-4">
        <div class="custom-control custom-switch mb-3">
            <input type="hidden" name="allow_comments" value="0">
            <input type="checkbox" name="allow_comments" value="1" class="custom-control-input" id="allowComments"
                @checked(old('allow_comments', $lessonContent->allow_comments ?? false))>
            <label class="custom-control-label" for="allowComments">អនុញ្ញាតមតិយោបល់</label>
        </div>
    </div>

    <div class="col-md-4">
        <div class="custom-control custom-switch mb-3">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" value="1" class="custom-control-input" id="isPublished"
                @checked(old('is_published', $lessonContent->is_published ?? true))>
            <label class="custom-control-label" for="isPublished">បានបោះផ្សាយ</label>
        </div>
    </div>

</div>

@push('scripts')
    <!-- Load CKEditor 5 from CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            let bodyEditor = null;
            let summaryEditor = null;

            // Initialize CKEditor 5 on body textarea
            const bodyTextarea = document.querySelector('textarea[name="body"]');
            if (bodyTextarea) {
                ClassicEditor
                    .create(bodyTextarea, {
                        toolbar: {
                            items: [
                                'heading', '|',
                                'bold', 'italic', '|',
                                'bulletedList', 'numberedList', '|',
                                'outdent', 'indent', '|',
                                'blockQuote', 'insertTable', 'mediaEmbed', '|',
                                'undo', 'redo'
                            ]
                        }
                    })
                    .then(editor => {
                        bodyEditor = editor;
                        editor.model.document.on('change:data', () => {
                            bodyTextarea.value = editor.getData();
                        });
                    })
                    .catch(error => {
                        console.error('Error initializing CKEditor on body:', error);
                    });
            }

            // Initialize CKEditor 5 on summary textarea
            const summaryTextarea = document.querySelector('textarea[name="summary"]');
            if (summaryTextarea) {
                ClassicEditor
                    .create(summaryTextarea, {
                        toolbar: {
                            items: [
                                'bold', 'italic', '|',
                                'bulletedList', 'numberedList', '|',
                                'undo', 'redo'
                            ]
                        }
                    })
                    .then(editor => {
                        summaryEditor = editor;
                        editor.model.document.on('change:data', () => {
                            summaryTextarea.value = editor.getData();
                        });
                    })
                    .catch(error => {
                        console.error('Error initializing CKEditor on summary:', error);
                    });
            }

            // Dynamic show/hide of fields based on content type
            const $contentTypeSelect = $('select[name="content_type"]');
            
            function toggleFields() {
                const type = $contentTypeSelect.val();
                
                // Hide all optional fields first
                $('#group-body, #group-external-url, #group-video-url, #group-file-path, #group-duration, #group-max-score, #group-passing-score').hide();
                
                // Show fields based on selected type
                if (type === 'lesson' || type === 'page') {
                    $('#group-body').show();
                    $('#group-duration').show();
                } else if (type === 'video') {
                    $('#group-video-url').show();
                    $('#group-duration').show();
                } else if (type === 'file') {
                    $('#group-file-path').show();
                } else if (type === 'url') {
                    $('#group-external-url').show();
                } else if (type === 'assignment') {
                    $('#group-body').show();
                    $('#group-duration').show();
                    $('#group-max-score').show();
                    $('#group-passing-score').show();
                } else if (type === 'quiz') {
                    $('#group-duration').show();
                    $('#group-max-score').show();
                    $('#group-passing-score').show();
                } else if (type === 'forum') {
                    $('#group-body').show();
                    $('#group-duration').show();
                }
            }

            if ($contentTypeSelect.length) {
                $contentTypeSelect.on('change', toggleFields);
                toggleFields(); // Initial call
            }
        });
    </script>
    <style>
        .ck-editor__editable_inline {
            min-height: 250px;
        }
        /* Make summary editor shorter */
        #group-summary .ck-editor__editable_inline {
            min-height: 100px;
        }
        /* Style fixes for bootstrap 4 inside AdminLTE */
        .ck-editor {
            width: 100% !important;
        }
    </style>
@endpush
