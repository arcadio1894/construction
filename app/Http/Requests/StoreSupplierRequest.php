<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'business_name' => 'required|string|max:255',
            //'ruc' => 'required|digits:11|string|unique:suppliers,RUC',
            'ruc' => 'required|string|unique:suppliers,RUC',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255'
        ];
    }

    public function messages()
    {
        return [
            'business_name.required' => 'La :attribute es obligatoria.',
            'business_name.string' => 'La :attribute debe contener caracteres válidos.',
            'business_name.max' => 'La :attribute debe contener máximo 255 caracteres.',
            'business_name.unique' => 'La :attribute ya existe en la base de datos.',

            'ruc.required' => 'El :attribute es obligatorio.',
            'ruc.string' => 'El :attribute debe contener caracteres válidos.',
            /*'ruc.digits' => 'El :attribute es demasiado largo.',*/
            'ruc.unique' => 'El :attribute ya existe en la base de datos.',
            /*'ruc.numeric' => 'El :attribute debe ser numerico.',*/

            'address.string' => 'La :attribute debe contener caracteres válidos.',
            'address.max' => 'La :attribute debe contener máximo 255 caracteres.',

            'phone.string' => 'La :attribute debe contener caracteres válidos.',
            'phone.max' => 'La :attribute debe contener máximo 255 caracteres.',

            'email.max' => 'La :attribute debe contener máximo 255 caracteres.',
            'email.string' => 'La :attribute debe contener caracteres válidos.',
            'email.email' => 'La :attribute no tiene formato de email correcto.',

        ];
    }

    public function attributes()
    {
        return [
            'business_name' => 'Razón Sacial',
            'RUC' => 'RUC del proveedor',
            'address' => 'dirección del proveedor',
            'phone' => 'teléfono del proveedor',
            'email' => 'email del proveedor',
        ];
    }
}
