<?php

namespace App\Http\Requests\LessonContentRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLessonContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'module_number' => ['required', 'integer', 'min:1'],
            'module_title' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('lesson_contents', 'slug')->ignore($this->route('id')),
            ],
            'content_type' => ['required', Rule::in(['lesson', 'page', 'video', 'file', 'url', 'assignment', 'quiz', 'forum'])],
            'summary' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'external_url' => ['nullable', 'url', 'max:255'],
            'file_path' => ['nullable', 'string', 'max:255'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'position' => ['required', 'integer', 'min:1'],
            'available_from' => ['nullable', 'date'],
            'available_until' => ['nullable', 'date', 'after_or_equal:available_from'],
            'completion_required' => ['nullable', 'boolean'],
            'visibility' => ['required', Rule::in(['visible', 'hidden', 'scheduled'])],
            'max_score' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'passing_score' => ['nullable', 'numeric', 'min:0', 'lte:max_score'],
            'allow_comments' => ['nullable', 'boolean'],
            'metadata' => ['nullable', 'json'],
            'is_published' => ['nullable', 'boolean'],
        ];
    }
}
