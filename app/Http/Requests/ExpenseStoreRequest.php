<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bill_id' => 'required|exists:bills,id',
            'worker_id' => 'required|exists:workers,id',
            'date_expense' => 'required|date_format:d/m/Y',
            'total' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'bill_id.required' => 'El :attribute es obligatorio.',
            'bill_id.exists' => 'El :attribute no existe en la base de datos.',
            'worker_id.required' => 'El :attribute es obligatorio.',
            'worker_id.exists' => 'El :attribute no existe en la base de datos.',
            'date_expense.required' => 'La :attribute es obligatorio.',
            'date_expense.date_format' => 'La :attribute debe tener el formato d/m/Y.',
            'total.required' => 'El :attribute es obligatorio.',
            'total.numeric' => 'El :attribute debe ser numérico.',
            'total.min' => 'El :attribute debe ser mínimo 0.',
        ];
    }

    public function attributes()
    {
        return [
            'bill_id' => 'ID del tipo de gasto',
            'worker_id' => 'ID del trabajador',
            'date_expense' => 'fecha del gasto',
            'total' => 'monto del gasto'
        ];
    }
}
