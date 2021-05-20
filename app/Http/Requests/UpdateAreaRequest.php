<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAreaRequest extends FormRequest
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
            'area_id' => 'required|exists:areas,id',
            'name' => 'required|string|max:255',
            'comment' => 'string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'area_id.required' => 'El :attribute es obligatorio.',
            'area_id.exists' => 'El :attribute debe existir en la base de datos.',
            'name.required' => 'El :attribute es obligatorio.',
            'name.string' => 'El :attribute debe contener caracteres válidos.',
            'name.max' => 'El :attribute debe contener máximo 255 caracteres.',
            'comment.string' => 'La :attribute debe contener caracteres válidos.',
            'comment.max' => 'La :attribute debe contener máximo 255 caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'area_id' => 'id del área',
            'name' => 'nombre del área',
            'comment' => 'comentario del área'
        ];
    }
}
