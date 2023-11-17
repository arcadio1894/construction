<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FifthCategoryStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'worker_id' => 'required|exists:workers,id',
            'amount.*' => 'required|numeric',
            'date.*' => 'required',
            'selectYear' => 'required|integer',
            'selectMonth' => 'required|integer',
            'totalAmount' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'worker_id.required' => 'El :attribute es obligatorio.',
            'worker_id.exists' => 'El :attribute no existe en la base de datos.',
            'amount.*.required' => 'El :attribute es obligatorio.',
            'amount.*.numeric' => 'El :attribute no es un número válido.',
            'date.*.required' => 'La :attribute es obligatorio.',
            'date.*.date_format' => 'La :attribute no tiene el formato dd/mm/yyyy.',
            'selectMonth.require'=>'El :attribute es obligatorio',
            'selectMonth.integer'=>'El :attribute ingresado es incorrecto',
            'selectYear.require'=>'El :attribute es obligatorio',
            'selectYear.integer'=>'El :attribute ingresado es incorrecto',
            'totalAmount.require'=>'El :attribute es obligatorio',
            'totalAmount.numeric'=>'El :attribute no es un número válido',
        ];
    }

    public function attributes()
    {
        return [
            'worker_id' => 'trabajador',
            'amount.*' => 'monto a pagar',
            'date.*' => 'fecha a pagar',
            'selectMonth' => 'mes',
            'selectYear' => 'año',
            'totalAmount' => 'monto total'
        ];
    }
}
