<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderPurchaseRequest extends FormRequest
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
            'purchase_order' => 'required',
            'purchase_condition' => 'nullable|string',
            'observation' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'purchase_order.required' => 'El :attribute es obligatorio.',
            'purchase_condition.string' => 'El :attribute debe contener caracteres válidos.',
            'observation.string' => 'La :attribute debe contener caracteres válidos.',
        ];
    }

    public function attributes()
    {
        return [
            'purchase_order' => 'código de la orden',
            'purchase_condition' => 'código',
            'observation' => 'fecha',
        ];
    }
}
