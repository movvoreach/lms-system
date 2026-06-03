<?php

namespace App\Http\Requests\SemesterRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSemesterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'academic_year_id' => ['required', 'exists:academic_years,academic_year_id'],
            'study_year' => ['required', 'integer', 'min:1', 'max:4'],
            'term_number' => ['required', 'integer', 'in:1,2'],
            'semester_name' => [
                'required',
                'string',
                'max:50',
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ];
    }
}
