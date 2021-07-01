<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteBrandRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'brand_id.required' => 'El :attribute es obligatorio.',
            'brand_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'brand_id' => 'id de la marca de material'
        ];
    }
}
