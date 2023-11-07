<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryEquipmentsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'description' => 'required|unique:category_equipments,description|string|max:190',
            'image' => 'nullable|file|mimes:jpg,png,jpeg|max:10240'
        ];
    }
    public function messages()
    {
        return [
            'description.required' => 'La :attribute es obligatoria.',
            'description.max' => 'La :attribute no puede ser mayor a :max caracteres.',
            'description.unique' => 'Esta categoría ya fue registrada',
            'image.mimes' => 'La :attribute debe tener extensión:jpeg, jpg, png',
            'image.max' => 'La :attribute no debe ser mayor a :max kilobytes.',
        ];
    }

    public function attributes()
    {
        return [
            'description' => 'descripción',
            'image' => 'imagen'
        ];
    }
}
