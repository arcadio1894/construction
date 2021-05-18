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
            'materialtype_id' => 'required|exists:material_types,id',
            'name' => 'required|string|max:255',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
                        
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
                        
            'length.numeric' => 'El :attribute debe ser numerico.',            
            'length.min' => 'El :attribute debe ser mayor a 0.',
            
            'width.numeric' => 'El :attribute debe ser numerico.',            
            'width.min' => 'El :attribute debe ser mayor a 0.',

            'weight.numeric' => 'El :attribute debe ser numerico.',            
            'weight.min' => 'El :attribute debe ser mayor a 0.',

            
        ];
    }

    public function attributes()
    {
        return [
            'materialType_id' => 'id del tipo de material',
            'name' => 'nombre del tipo de material',
            'length' => 'largo del tipo de material',
            'width' => 'ancho del tipo de material',
            'weight' => 'peso del tipo de material',
            
        ];
    }
}
