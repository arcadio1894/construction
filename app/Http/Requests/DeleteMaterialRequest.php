<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteMaterialRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'material_id' => 'required|exists:materials,id',
        ];
    }

    public function messages()
    {
        return [
            'material_id.required' => 'El :attribute es obligatorio.',
            'material_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'material_id' => 'id del material'
        ];
    }
}
