<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubtypeRequest extends FormRequest
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
            'material_type_id' => 'required|exists:material_types,id',

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

            'material_type_id.required' => 'El :attribute es obligatoria.',
            'material_type_id.exists' => 'El :attribute no existe en la base de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre del subtipo',
            'description' => 'descripción del subtipo',
            'material_type_id' => 'tipo del subtipo',
        ];
    }
}
