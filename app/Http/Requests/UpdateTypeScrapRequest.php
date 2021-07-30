<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTypeScrapRequest extends FormRequest
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
            'typeScrap_id' => 'required|exists:typescraps,id',
            'name' => 'required|string|max:255',
            'width' => 'required|numeric|between:0,99999.99',
            'length' => 'required|numeric|between:0,99999.99',
        ];
    }

    public function messages()
    {
        return [
            'typeScrap_id.required' => 'El :attribute es obligatoria.',
            'typeScrap_id.exists' => 'El :attribute no existe en la based de datos.',

            'name.required' => 'El :attribute es obligatoria.',
            'name.string' => 'El :attribute debe contener caracteres válidos.',
            'name.max' => 'El :attribute debe contener máximo 255 caracteres.',

            'width.required' => 'El :attribute es obligatorio.',
            'width.numeric' => 'El :attribute debe ser un número.',
            'width.between' => 'El :attribute esta fuera del rango numérico.',

            'length.required' => 'El :attribute es obligatorio.',
            'length.numeric' => 'El :attribute debe ser un número.',
            'length.between' => 'El :attribute esta fuera del rango numérico.',

        ];
    }

    public function attributes()
    {
        return [
            'typeScrap_id' => 'id del tipo de retacería',
            'name' => 'nombre del tipo de retacería',
            'width' => 'ancho del tipo de retacería',
            'length' => 'largo del tipo de retacería',
        ];
    }
}
