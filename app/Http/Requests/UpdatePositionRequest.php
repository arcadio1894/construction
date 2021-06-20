<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePositionRequest extends FormRequest
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
            'comment' => 'string|max:255',
            'position_id' => 'required|exists:positions,id'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El :attribute es obligatorio.',
            'name.string' => 'El :attribute debe contener caracteres válidos.',
            'name.max' => 'El :attribute debe contener máximo 255 caracteres.',
            'name.unique' => 'El :attribute ya existe en la base de datos.',
            'comment.string' => 'La :attribute debe contener caracteres válidos.',
            'comment.max' => 'La :attribute debe contener máximo 255 caracteres.',
            'position_id.required' => 'El :attribute es obligatorio.',
            'position_id.exists' => 'El :attribute no existe en la base de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre de la posición',
            'comment' => 'comentario de la posición',
            'position_id' => 'posición'
        ];
    }
}
