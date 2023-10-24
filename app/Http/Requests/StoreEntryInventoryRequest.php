<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntryInventoryRequest extends FormRequest
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
            'referral_guide' => 'nullable|string|min:5|max:255',
            'purchase_order' => 'nullable|string|min:5|max:255',
            'invoice' => 'nullable|string|min:5|max:255',
            'entry_type' => 'required',
            'deferred_invoice' => 'nullable',
            'currency_invoice' => 'nullable',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'items' => 'required',
            //'image' => 'nullable|mimetypes:image/jpeg,image/jpg,image/png,application/pdf',
            'date_invoice' => 'required'
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
            'items.required' => 'Los :attribute son obligatorio.',
            //'image.mimetypes' => 'La :attribute no tiene el formato requerido.',
            'date_invoice.required' => 'La :attribute es obligatoria.',
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
            'items' => 'items',
            'deferred_invoice' => 'opción diferido',
            'image' => 'imagen/PDF'
        ];
    }
}
