<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteSupplierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
        ];
    }

    public function messages()
    {
        return [
            'supplier_id.required' => 'El :attribute es obligatorio.',
            'supplier_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'supplier_id' => 'id del proveedor'
        ];
    }
}
