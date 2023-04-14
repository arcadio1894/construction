<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FifthCategoryUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'worker_id' => 'required|exists:workers,id',
            'fifthCategory_id' => 'required|exists:fifth_categories,id',
            'amount' => 'required|numeric',
            'date' => 'required|date_format:d/m/Y'
        ];
    }

    public function messages()
    {
        return [
            'worker_id.required' => 'El :attribute es obligatorio.',
            'worker_id.exists' => 'El :attribute no existe en la base de datos.',
            'fifthCategory_id.required' => 'El :attribute es obligatorio.',
            'fifthCategory_id.exists' => 'El :attribute no existe en la base de datos.',
            'amount.required' => 'El :attribute es obligatorio.',
            'amount.numeric' => 'El :attribute no es un número válido.',
            'date.required' => 'La :attribute es obligatorio.',
            'date.date_format' => 'La :attribute no tiene el formato dd/mm/yyyy.'
        ];
    }

    public function attributes()
    {
        return [
            'worker_id' => 'trabajador',
            'fifthCategory_id' => 'id del pago',
            'amount' => 'monto a pagar',
            'date' => 'fecha de pago'
        ];
    }
}
