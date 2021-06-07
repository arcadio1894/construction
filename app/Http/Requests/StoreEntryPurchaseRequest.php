<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntryPurchaseRequest extends FormRequest
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
            'referral_guide' => 'required|string|min:5|max:255',
            'purchase_order' => 'required|string|min:5|max:255',
            'invoice' => 'required|string|min:5|max:255',
            'entry_type' => 'required',
            'items' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'referral_guide.required' => 'El :attribute es obligatorio.',
            'referral_guide.string' => 'El :attribute debe contener caracteres válidos.',
            'referral_guide.min' => 'El :attribute debe contener mínimo 5 caracteres.',
            'referral_guide.max' => 'El :attribute debe contener máximo 255 caracteres.',
            'purchase_order.required' => 'La :attribute es obligatorio.',
            'purchase_order.string' => 'La :attribute debe contener caracteres válidos.',
            'purchase_order.min' => 'La :attribute debe contener mínimo 5 caracteres.',
            'purchase_order.max' => 'La :attribute debe contener máximo 255 caracteres.',
            'invoice.required' => 'La :attribute es obligatorio.',
            'invoice.string' => 'La :attribute debe contener caracteres válidos.',
            'invoice.min' => 'La :attribute debe contener mínimo 5 caracteres.',
            'invoice.max' => 'La :attribute debe contener máximo 255 caracteres.',
            'entry_type.required' => 'La :attribute es obligatorio.',
            'items.required' => 'Los :attribute son obligatorio.',
        ];
    }

    public function attributes()
    {
        return [
            'referral_guide' => 'guía de remisión',
            'purchase_order' => 'orden de compra',
            'invoice' => 'factura',
            'entry_type' => 'tipo de entrada',
            'items' => 'items'
        ];
    }
}
