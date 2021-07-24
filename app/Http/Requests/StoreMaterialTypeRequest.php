<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialTypeRequest extends FormRequest
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
            'description' => 'nullable|string|max:255',
            'subcategory_id' => 'required|exists:subcategories,id',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El :attribute es obligatoria.',
            'name.string' => 'El :attribute debe contener caracteres válidos.',
            'name.max' => 'El :attribute debe contener máximo 255 caracteres.',

            'description.string' => 'El :attribute debe contener caracteres válidos.',
            'description.max' => 'El :attribute debe contener máximo 255 caracteres.',

            'subcategory_id.required' => 'El :attribute es obligatoria.',
            'subcategory_id.exists' => 'El :attribute no existe en la base de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre del tipo',
            'description' => 'descripción del tipo',
            'subcategory_id' => 'subcategoría del tipo',
        ];
    }
}
