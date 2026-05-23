<?php

namespace App\Http\Requests\TeacherRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => ['nullable', 'exists:courses,id'],
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($this->teacherUserId(), 'user_id'),
            ],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($this->teacherUserId(), 'user_id'),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'employee_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('teachers', 'employee_number')->ignore($this->route('id'), 'teacher_id'),
            ],
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'specialization' => ['nullable', 'string', 'max:200'],
            'academic_rank' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'max:30'],
        ];
    }

    private function teacherUserId(): ?int
    {
        return \App\Models\Teacher::query()
            ->where('teacher_id', $this->route('id'))
            ->value('user_id');
    }
}
