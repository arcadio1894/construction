<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'business_name' => 'required|string|max:255',
            /*'ruc' => 'required|digits:11|string',*/
            'ruc' => 'required|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
        ];
    }

    public function messages()
    {
        return [

            'supplier_id.required' => 'El :attribute es obligatorio.',
            'supplier_id.exists' => 'El :attribute debe existir en la base de datos.',

            'business_name.required' => 'La :attribute es obligatoria.',
            'business_name.string' => 'La :attribute debe contener caracteres válidos.',
            'business_name.max' => 'La :attribute debe contener máximo 255 caracteres.',

            'ruc.required' => 'El :attribute es obligatorio.',
            'ruc.string' => 'El :attribute debe contener caracteres válidos.',
            /*'ruc.digits' => 'El :attribute es demasiado largo.',
            'ruc.numeric' => 'El :attribute debe ser numerico.',*/

            'address.string' => 'La :attribute debe contener caracteres válidos.',
            'address.max' => 'La :attribute debe contener máximo 255 caracteres.',

            'phone.string' => 'La :attribute debe contener caracteres válidos.',
            'phone.max' => 'La :attribute debe contener máximo 255 caracteres.',

            'email.string' => 'La :attribute debe contener caracteres válidos.',
            'email.max' => 'La :attribute debe contener máximo 255 caracteres.',
            'email.email' => 'La :attribute debe tener formato de email.',

        ];
    }

    public function attributes()
    {
        return [
            'supplier_id' => 'id del proveedor',
            'business_name' => 'Razón Social del proveedor',
            'ruc' => 'RUC del proveedor',
            'address' => 'dirección del proveedor',
            'phone' => 'teléfono del proveedor',
            'email' => 'email del proveedor',
        ];
    }
}
