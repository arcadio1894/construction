<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GratificationUpdateRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'date' => 'required|date_format:d/m/Y'
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
            'gratification_id.exists' => 'El :attribute no existe en la base de datos.',
            'amount.required' => 'El :attribute es obligatorio.',
            'amount.numeric' => 'El :attribute no es un número válido.',
            'date.required' => 'La :attribute es obligatorio.',
            'date.date_format' => 'La :attribute no tiene el formato dd/mm/yyyy.'
        ];
    }

    public function attributes()
    {
        return [
            'period_id' => 'periodo de gratificación',
            'worker_id' => 'trabajador',
            'gratification_id' => 'id de gratificación',
            'amount' => 'monto a pagar',
            'date' => 'fecha de pago'
        ];
    }
}
