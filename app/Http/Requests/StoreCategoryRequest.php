<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'string|max:255',
            

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El :attribute es obligatoria.',
            'name.string' => 'El :attribute debe contener caracteres válidos.',
            'name.max' => 'El :attribute debe contener máximo 255 caracteres.',
            
            
            'description.string' => 'La :attribute debe contener caracteres válidos.',            
            'description.max' => 'La :attribute es demasiado largo.',

        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre de categoría de material',
            'description' => 'descripción de categoría de material',
           
            
        ];
    }
}
