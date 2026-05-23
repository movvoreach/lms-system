<?php

namespace App\Http\Requests\CourseRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:course_categories,id'],
            'department_id' => ['required', 'exists:departments,department_id'],
            'semester_id' => ['nullable', 'exists:semesters,semester_id'],
            'title' => ['required', 'string', 'max:255'],
            'code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('courses', 'code')->ignore($this->route('id')),
            ],
            'description' => ['nullable', 'string'],
            'duration_hours' => ['nullable', 'integer', 'min:0'],
            'fee' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
