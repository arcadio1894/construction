<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteExamplerRequest extends FormRequest
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
            'exampler_id' => 'required|exists:examplers,id',
        ];
    }

    public function messages()
    {
        return [
            'exampler_id.required' => 'El :attribute es obligatorio.',
            'exampler_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'exampler_id' => 'id del modelo de material'
        ];
    }
}
