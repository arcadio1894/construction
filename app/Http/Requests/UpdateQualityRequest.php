<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQualityRequest extends FormRequest
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
            'quality_id' => 'required|exists:qualities,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'quality_id.required' => 'El :attribute es obligatoria.',
            'quality_id.exists' => 'El :attribute no existe en la base de datos.',

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
            'quality_id' => 'id de la calidad',
            'name' => 'nombre de calidad de material',
            'description' => 'descripción de la calidad de material',
        ];
    }
}
