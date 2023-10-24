<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEntryInventoryRequest extends FormRequest
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
            'date_invoice' => 'required',
            'referral_guide' => 'nullable|string|min:5|max:255',
            'purchase_order' => 'nullable|string|min:5|max:255',
            'invoice' => 'nullable|string|min:5|max:255',
            'deferred_invoice' => 'nullable',
            'entry_type' => 'required',
            'supplier_id' => 'nullable|exists:suppliers,id',

        ];
    }

    public function messages()
    {
        return [
            'referral_guide.string' => 'El :attribute debe contener caracteres válidos.',
            'referral_guide.min' => 'El :attribute debe contener mínimo 5 caracteres.',
            'referral_guide.max' => 'El :attribute debe contener máximo 255 caracteres.',
            'purchase_order.string' => 'La :attribute debe contener caracteres válidos.',
            'purchase_order.min' => 'La :attribute debe contener mínimo 5 caracteres.',
            'purchase_order.max' => 'La :attribute debe contener máximo 255 caracteres.',
            'invoice.string' => 'La :attribute debe contener caracteres válidos.',
            'invoice.min' => 'La :attribute debe contener mínimo 5 caracteres.',
            'invoice.max' => 'La :attribute debe contener máximo 255 caracteres.',
            'entry_type.required' => 'La :attribute es obligatorio.',
            'supplier_id.exists' => 'El :attribute no existe en la base de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'date_invoice' => 'fecha de factura',
            'referral_guide' => 'guía de remisión',
            'purchase_order' => 'orden de compra',
            'invoice' => 'factura',
            'entry_type' => 'tipo de entrada',
            'supplier_id' => 'proveedor',
            'deferred_invoice' => 'opción diferida',
            'image' => 'imagen/PDF factura',
            'imageOb' => 'imagen/PDF observacion'
        ];
    }
}
