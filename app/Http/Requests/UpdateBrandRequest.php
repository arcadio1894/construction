<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
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
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'comment' => 'string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'brand_id.required' => 'El :attribute es obligatoria.',
            'brand_id.exists' => 'El :attribute no existe en la base de datos.',

            'name.required' => 'El :attribute es obligatoria.',
            'name.string' => 'El :attribute debe contener caracteres válidos.',
            'name.max' => 'El :attribute debe contener máximo 255 caracteres.',

            'comment.string' => 'La :attribute debe contener caracteres válidos.',
            'comment.max' => 'La :attribute es demasiado largo.',

        ];
    }

    public function attributes()
    {
        return [
            'brand_id' => 'id de la marca',
            'name' => 'nombre de marca de material',
            'comment' => 'descripción de marca de material',
        ];
    }
}
