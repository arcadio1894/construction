<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteMaterialTypeRequest extends FormRequest
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
            'materialType_id' => 'required|exists:material_types,id',
        ];
    }

    public function messages()
    {
        return [
            'materialType_id.required' => 'El :attribute es obligatorio.',
            'materialType_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'materialType_id' => 'id del tipo de material'
        ];
    }
}
