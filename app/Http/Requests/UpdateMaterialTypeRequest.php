<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaterialTypeRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'length' => 'string|max:255',
            'width' => 'string|max:255',
            'weight' => 'string|max:255',
                        
        ];
    }

    public function messages()
    {
        return [

            'materialType_id.required' => 'El :attribute es obligatorio.',
            'materialType_id.exists' => 'El :attribute debe existir en la base de datos.',

            'name.required' => 'La :attribute es obligatoria.',
            'name.string' => 'La :attribute debe contener caracteres válidos.',
            'name.max' => 'La :attribute debe contener máximo 255 caracteres.',

            'length.string' => 'La :attribute debe contener caracteres válidos.',
            'length.max' => 'La :attribute debe contener máximo 255 caracteres.',
            
            'width.string' => 'La :attribute debe contener caracteres válidos.',
            'width.max' => 'La :attribute debe contener máximo 255 caracteres.',

            'weight.string' => 'La :attribute debe contener caracteres válidos.',
            'weight.max' => 'La :attribute debe contener máximo 255 caracteres.',

            
        ];
    }

    public function attributes()
    {
        return [
            'materialType_id' => 'id del cliente',
            'name' => 'nombre del tipo de material',
            'length' => 'largo del tipo de material',
            'width' => 'ancho del tipo de material',
            'weight' => 'peso del tipo de material',
            
        ];
    }
}
