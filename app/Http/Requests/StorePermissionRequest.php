<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
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
            'name' => 'required|string|min:5|max:255|unique:permissions,name',
            'description' => 'required|string|min:5|max:255'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El :attribute es obligatorio.',
            'name.string' => 'El :attribute debe contener caracteres válidos.',
            'name.min' => 'El :attribute debe contener mínimo 5 caracteres.',
            'name.max' => 'El :attribute debe contener máximo 255 caracteres.',
            'name.unique' => 'El :attribute ya existe en la base de datos.',
            'description.required' => 'La :attribute es obligatorio.',
            'description.string' => 'La :attribute debe contener caracteres válidos.',
            'description.min' => 'La :attribute debe contener mínimo 5 caracteres.',
            'description.max' => 'La :attribute debe contener máximo 255 caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre del permiso',
            'description' => 'descripción del permiso'
        ];
    }
}
