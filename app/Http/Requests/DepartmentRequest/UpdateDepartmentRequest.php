<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $department = $this->route('department');
        $departmentId = is_object($department) ? $department->getKey() : $department;

        return [
            'faculty_id' => ['required', 'exists:faculties,faculty_id'],
            'department_code' => [
                'required',
                'string',
                'max:30',
                Rule::unique('departments', 'department_code')->ignore($departmentId, 'department_id'),
            ],
            'department_name' => ['required', 'string', 'max:150'],
            'deans' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'faculty_id.required' => 'សូមជ្រើសរើសមហាវិទ្យាល័យ។',
            'faculty_id.exists' => 'The selected faculty is not valid.',
            'department_code.required' => 'សូមបញ្ចូលកូដដេប៉ាតឺម៉ង់។',
            'department_code.unique' => 'កូដដេប៉ាតឺម៉ង់នេះមានប្រើរួចហើយ។',
            'department_code.max' => 'The department code may not be greater than 30 characters.',
            'department_name.required' => 'សូមបញ្ចូលឈ្មោះដេប៉ាតឺម៉ង់។',
            'department_name.max' => 'The department name may not be greater than 150 characters.',
            'deans.max' => 'The dean name may not be greater than 255 characters.',
        ];
    }
}

