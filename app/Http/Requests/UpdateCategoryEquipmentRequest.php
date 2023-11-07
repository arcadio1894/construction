<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryEquipmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'description' => 'required|string',
            'editImage' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'El :attribute es obligatorio.',
            'description.string' => 'El :attribute debe ser una cadena de caracteres.',
            'editImage.image' => 'La :attribute debe ser un archivo de imagen válido.',
            'editImage.mimes' => 'La :attribute debe ser de tipo jpg, jpeg, png o gif.',
            'editImage.max' => 'La :attribute no debe superar :max kilobytes.',
        ];
    }

    public function attributes()
    {
        return [
            'description' => 'Descripción',
            'editImage' => 'Imagen',
        ];
    }
}
