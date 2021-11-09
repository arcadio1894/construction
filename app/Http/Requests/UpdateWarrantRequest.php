<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarrantRequest extends FormRequest
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
            'warrant_id' => 'required|exists:warrants,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'warrant_id.required' => 'El :attribute es obligatoria.',
            'warrant_id.exists' => 'El :attribute no existe en la base de datos.',

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
            'warrant_id' => 'id de la cédula',
            'name' => 'nombre de cédula de material',
            'description' => 'descripción de la cédula de material',
        ];
    }
}
