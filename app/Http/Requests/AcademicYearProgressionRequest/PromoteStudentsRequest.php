<?php

namespace App\Http\Requests\AcademicYearProgressionRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromoteStudentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from_academic_year_id' => ['required', 'exists:academic_years,academic_year_id'],
            'to_academic_year_id' => [
                'required',
                'exists:academic_years,academic_year_id',
                'different:from_academic_year_id',
            ],
            'student_ids' => ['nullable', 'array'],
            'student_ids.*' => ['integer', 'exists:students,student_id'],
            'promotion_type' => ['required', Rule::in(['manual', 'batch', 'auto'])],
            'target_status' => ['required', 'string', 'max:30'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
