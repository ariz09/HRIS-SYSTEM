<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeEmploymentHistoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'histories' => 'required|array|min:1',
            'histories.*.id' => 'nullable|exists:employee_employment_histories,id',
            'histories.*.job_title' => 'required|string|max:255',
            'histories.*.job_description' => 'nullable|string',
            'histories.*.start_date' => 'required|date',
            'histories.*.end_date' => 'nullable|date|after:histories.*.start_date',
        ];
    }
}