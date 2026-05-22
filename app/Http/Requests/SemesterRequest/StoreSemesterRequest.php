<?php

namespace App\Http\Requests\SemesterRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSemesterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'academic_year_id' => ['required', 'exists:academic_years,academic_year_id'],
            'semester_name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('semesters', 'semester_name')
                    ->where('academic_year_id', $this->input('academic_year_id')),
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ];
    }
}
