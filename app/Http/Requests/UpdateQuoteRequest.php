<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuoteRequest extends FormRequest
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
            'quote_id' => 'required|exists:quotes,id',
            'code_quote' => 'nullable|string',
            'code_description' => 'nullable|string',
            'way_to_pay' => 'nullable|string',
            'delivery_time' => 'nullable|string',
            'utility' => 'nullable|numeric|between:0,99.99',
            'letter' => 'nullable|numeric|between:0,99.99',
            'taxes' => 'nullable|numeric|between:0,99.99'
        ];
    }

    public function messages()
    {
        return [
            'quote_id.required' => 'El :attribute es obligatoria.',
            'quote_id.exists' => 'El :attribute no existe en la base de datos.',
            'code_description.string' => 'El :attribute debe contener caracteres válidos.',
            'code_quote.string' => 'El :attribute debe contener caracteres válidos.',
            'way_to_pay.string' => 'La :attribute debe contener caracteres válidos.',
            'delivery_time.string' => 'El :attribute debe contener caracteres válidos.',
            'utility.numeric' => 'La :attribute debe ser un valor numérico.',
            'utility.between' => 'La :attribute debe estar en el rango 0 a 99.99.',
            'letter.numeric' => 'La :attribute debe ser un valor numérico.',
            'letter.between' => 'La :attribute debe estar en el rango 0 a 99.99.',
            'taxes.numeric' => 'La :attribute debe ser un valor numérico.',
            'taxes.between' => 'La :attribute debe estar en el rango 0 a 99.99.',
        ];
    }

    public function attributes()
    {
        return [
            'quote_id' => 'id',
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
