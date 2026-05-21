<?php

namespace App\Http\Requests\FacultyRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreFacultyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'faculty_code' => ['required', 'string', 'max:30', 'unique:faculties,faculty_code'],
            'faculty_name' => ['required', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'faculty_code.required' => 'សូមបញ្ចូលកូដមហាវិទ្យាល័យ។',
            'faculty_code.unique' => 'កូដមហាវិទ្យាល័យនេះមានប្រើរួចហើយ។',
            'faculty_name.required' => 'សូមបញ្ចូលឈ្មោះមហាវិទ្យាល័យ។',
        ];
    }
}
