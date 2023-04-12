<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GratificationDeleteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'period_id' => 'required|exists:grati_periods,id',
            'worker_id' => 'required|exists:workers,id',
            'gratification_id' => 'required|exists:gratifications,id',
        ];
    }

    public function messages()
    {
        return [
            'period_id.required' => 'El :attribute es obligatorio.',
            'period_id.exists' => 'El :attribute no existe en la base de datos.',
            'worker_id.required' => 'El :attribute es obligatorio.',
            'worker_id.exists' => 'El :attribute no existe en la base de datos.',
            'gratification_id.required' => 'El :attribute es obligatorio.',
            'gratification_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'period_id' => 'periodo de gratificación',
            'worker_id' => 'trabajador',
            'gratification_id' => 'id de gratificación',
        ];
    }
}
