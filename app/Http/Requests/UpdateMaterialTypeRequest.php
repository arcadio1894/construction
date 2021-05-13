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

            'name.required' => 'El :attribute es obligatoria.',
            'name.string' => 'El :attribute debe contener caracteres válidos.',
            'name.max' => 'El :attribute debe contener máximo 255 caracteres.',

            'length.string' => 'El :attribute debe contener caracteres válidos.',
            'length.max' => 'El :attribute debe contener máximo 255 caracteres.',
            
            'width.string' => 'El :attribute debe contener caracteres válidos.',
            'width.max' => 'El :attribute debe contener máximo 255 caracteres.',

            'weight.string' => 'El :attribute debe contener caracteres válidos.',
            'weight.max' => 'El :attribute debe contener máximo 255 caracteres.',

            
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
