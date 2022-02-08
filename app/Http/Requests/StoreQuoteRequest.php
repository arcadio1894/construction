<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
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
            'code_quote' => 'string',
            'code_description' => 'nullable|string',
            'way_to_pay' => 'nullable|string',
            'delivery_time' => 'nullable|string',
            'utility' => 'nullable|numeric|min:0',
            'letter' => 'nullable|numeric|min:0',
            'taxes' => 'nullable|numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'code_description.string' => 'El :attribute debe contener caracteres válidos.',
            'code_quote.string' => 'El :attribute debe contener caracteres válidos.',
            'way_to_pay.string' => 'La :attribute debe contener caracteres válidos.',
            'delivery_time.string' => 'El :attribute debe contener caracteres válidos.',
            'customer_id.exists' => 'El :attribute no existe en la base de datos.',
            'utility.numeric' => 'La :attribute debe ser un valor numérico.',
            'utility.min' => 'La :attribute no puede ser negativa.',
            'letter.numeric' => 'La :attribute debe ser un valor numérico.',
            'letter.min' => 'La :attribute no puede ser negativa.',
            'taxes.numeric' => 'La :attribute debe ser un valor numérico.',
            'taxes.min' => 'La :attribute no puede ser negativa.',
        ];
    }

    public function attributes()
    {
        return [
            'code_description' => 'descripción',
            'code_quote' => 'código',
            'date_quote' => 'fecha',
            'date_validate' => 'fecha válida',
            'way_to_pay' => 'formade pago',
            'delivery_time' => 'tiempo de entrega',
            'customer_id' => 'cliente',
            'equipments' => 'equipos',
            'utility' => 'utilidad',
            'letter' => 'letra',
            'taxes' => 'renta'
        ];
    }
}
