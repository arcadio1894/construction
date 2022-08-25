<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePorcentageQuoteRequest extends FormRequest
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
            'value' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [

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
            'name' => 'nombre del porcentaje de cotización',
            'value' => 'valor del porcentaje de cotización',
        ];
    }
}
