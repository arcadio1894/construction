<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntryPurchaseOrderRequest extends FormRequest
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
            'invoice' => 'required|string|min:5|max:255',
            'deferred_invoice' => 'nullable',
            'currency_invoice' => 'nullable',
            'items' => 'required',
            'image' => 'nullable|mimetypes:image/jpeg,image/jpg,image/png,application/pdf',
            'imageOb' => 'nullable|mimetypes:image/jpeg,image/jpg,image/png,application/pdf',
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
            'invoice.required' => 'La :attribute es obligatorio.',
            'invoice.string' => 'La :attribute debe contener caracteres válidos.',
            'invoice.min' => 'La :attribute debe contener mínimo 5 caracteres.',
            'invoice.max' => 'La :attribute debe contener máximo 255 caracteres.',
            'items.required' => 'Los :attribute son obligatorio.',
            'image.mimetypes' => 'La :attribute no tiene el formato requerido.',
            'date_invoice.required' => 'La :attribute es obligatoria.',
            'imageOb.mimetypes' => 'La :attribute no tiene el formato requerido.',
        ];
    }

    public function attributes()
    {
        return [
            'date_invoice' => 'fecha de factura',
            'referral_guide' => 'guía de remisión',
            'purchase_order' => 'orden de compra',
            'invoice' => 'factura',
            'items' => 'items',
            'deferred_invoice' => 'opción diferido',
            'image' => 'imagen/PDF',
            'imageOb' => 'imagen/PDF observacion'
        ];
    }
}
