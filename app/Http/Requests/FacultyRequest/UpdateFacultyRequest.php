<?php

namespace App\Http\Requests\FacultyRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFacultyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $facultyId = $this->route('id'); // IMPORTANT FIX

        return [
            'faculty_code' => [
                'required',
                'string',
                'max:30',
                Rule::unique('faculties', 'faculty_code')->ignore($facultyId, 'faculty_id'),
            ],
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
