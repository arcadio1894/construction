<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
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
            'comment' => 'nullable|string|max:255',
            'area_id' => 'required|exists:areas,id'
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
            'area_id.required' => 'El :attribute es obligatorio.',
            'area_id.exists' => 'El :attribute no existe en la base de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre del área',
            'comment' => 'comentario del área',
            'area_id' => 'área'
        ];
    }
}
