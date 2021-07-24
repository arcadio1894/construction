<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteSubtypeRequest extends FormRequest
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
            'subtype_id' => 'required|exists:subtypes,id',
        ];
    }

    public function messages()
    {
        return [
            'subtype_id.required' => 'El :attribute es obligatorio.',
            'subtype_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'subtype_id' => 'id del subtipo de material'
        ];
    }
}
