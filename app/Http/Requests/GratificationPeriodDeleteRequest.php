<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GratificationPeriodDeleteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'period_id' => 'required|exists:grati_periods,id',
        ];
    }

    public function messages()
    {
        return [
            'period_id.required' => 'El :attribute es obligatorio.',
            'period_id.exists' => 'El :attribute no existe en la base de datos.',

        ];
    }

    public function attributes()
    {
        return [
            'period_id' => 'periodo de gratificación',
        ];
    }
}
