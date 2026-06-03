<?php

namespace App\Http\Requests\StudentRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => ['nullable', 'exists:courses,id'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
            'department_id' => ['nullable', 'exists:departments,department_id'],
            'academic_year_id' => ['nullable', 'exists:academic_years,academic_year_id'],
            'semester_id' => ['nullable', 'exists:semesters,semester_id'],
            'study_year' => ['nullable', 'integer', 'min:1', 'max:4'],
            'term_number' => ['nullable', 'integer', 'in:1,2'],
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($this->studentUserId(), 'user_id'),
            ],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($this->studentUserId(), 'user_id'),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'student_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('students', 'student_number')->ignore($this->route('id'), 'student_id'),
            ],
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'max:30'],
        ];
    }

    private function studentUserId(): ?int
    {
        return \App\Models\Student::query()
            ->where('student_id', $this->route('id'))
            ->value('user_id');
    }
}
