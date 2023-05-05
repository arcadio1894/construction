<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderPurchaseFinanceRequest extends FormRequest
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
            'purchase_condition' => 'nullable|string',
            'observation' => 'nullable|string',
            'quote_supplier' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'service_order.required' => 'El :attribute es obligatorio.',
            'purchase_condition.string' => 'El :attribute debe contener caracteres válidos.',
            'observation.string' => 'La :attribute debe contener caracteres válidos.',
            'quote_supplier.string' => 'La :attribute debe contener caracteres válidos.',
        ];
    }

    public function attributes()
    {
        return [
            'service_order' => 'código de la orden',
            'purchase_condition' => 'código',
            'observation' => 'observación',
            'quote_supplier' => 'cotización de proveedor',
        ];
    }
}
