<?php

namespace App\Http\Requests\AcademicYearRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAcademicYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'year_label' => [
                'required',
                'string',
                'max:20',
                Rule::unique('academic_years', 'year_label')->ignore($this->route('id'), 'academic_year_id'),
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ];
    }
}
