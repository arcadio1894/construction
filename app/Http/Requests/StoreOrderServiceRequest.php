<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderServiceRequest extends FormRequest
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
            'service_order' => 'required',
            'service_condition' => 'nullable|string',
            'observation' => 'nullable|string',
            'quote_supplier' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'service_order.required' => 'El :attribute es obligatorio.',
            'service_condition.string' => 'La :attribute debe contener caracteres válidos.',
            'observation.string' => 'La :attribute debe contener caracteres válidos.',
            'quote_supplier.string' => 'La :attribute debe contener caracteres válidos.',
        ];
    }

    public function attributes()
    {
        return [
            'service_order' => 'código de la orden',
            'service_condition' => 'condición de pago',
            'observation' => 'observación',
            'quote_supplier' => 'cotización de proveedor',
        ];
    }
}
