<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GratificationPeriodStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'month' => 'required|numeric',
            'year' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'month.required' => 'El :attribute es obligatorio.',
            'month.numeric' => 'El :attribute no es un número válido.',
            'year.required' => 'El :attribute es obligatorio.',
            'year.numeric' => 'El :attribute no es un número válido.'
        ];
    }

    public function attributes()
    {
        return [
            'month' => 'mes',
            'year' => 'año'
        ];
    }
}
