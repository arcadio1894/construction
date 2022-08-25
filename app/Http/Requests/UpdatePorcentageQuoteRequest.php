<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePorcentageQuoteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'porcentage_id' => 'required|exists:porcentage_quotes,id',
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [

            'porcentage_id.required' => 'El :attribute es obligatorio.',
            'porcentage_id.exists' => 'El :attribute debe existir en la base de datos.',

            'name.required' => 'El :attribute es obligatoria.',
            'name.string' => 'El :attribute debe contener caracteres válidos.',
            'name.max' => 'El :attribute debe contener máximo 255 caracteres.',

            'value.required' => 'La :attribute debe contener caracteres válidos.',
            'value.numeric' => 'La :attribute debe ser un número.',
            'value.min' => 'La :attribute debe ser mínimo 0.',
        ];
    }

    public function attributes()
    {
        return [
            'porcentage_id' => 'id del porcentaje de cotización',
            'name' => 'nombre del porcentaje de cotización',
            'value' => 'valor del porcentaje de cotización',


        ];
    }
}
