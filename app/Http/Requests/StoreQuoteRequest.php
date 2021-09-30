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
            'code_quote' => 'required|string',
            'code_description' => 'required|string',
            'date_quote' => 'required',
            'date_validate' => 'required',
            'way_to_pay' => 'required|string',
            'delivery_time' => 'required|string',
            'customer_id' => 'required|exists:customers,id',
            'equipments' => 'required',
            'utility' => 'numeric|between:0,99.99',
            'letter' => 'numeric|between:0,99.99',
            'taxes' => 'numeric|between:0,99.99'
        ];
    }

    public function messages()
    {
        return [
            'code_description.required' => 'El :attribute es obligatorio.',
            'code_description.string' => 'El :attribute debe contener caracteres válidos.',
            'code_quote.required' => 'El :attribute es obligatorio.',
            'code_quote.string' => 'El :attribute debe contener caracteres válidos.',
            'date_quote.required' => 'El :attribute es obligatorio.',
            'date_validate.required' => 'La :attribute es obligatorio.',
            'way_to_pay.required' => 'La :attribute es obligatorio.',
            'way_to_pay.string' => 'La :attribute debe contener caracteres válidos.',
            'delivery_time.required' => 'El :attribute es obligatorio.',
            'delivery_time.string' => 'El :attribute debe contener caracteres válidos.',
            'customer_id.required' => 'El :attribute es obligatoria.',
            'customer_id.exists' => 'El :attribute no existe en la base de datos.',
            'equipments.required' => 'Los :attribute son obligatorios.',
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
