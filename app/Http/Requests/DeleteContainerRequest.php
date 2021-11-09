<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteContainerRequest extends FormRequest
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
            'container_id' => 'required|exists:containers,id',
        ];
    }

    public function messages()
    {
        return [
            'container_id.required' => 'El :attribute es obligatorio.',
            'container_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'container_id' => 'id del contenedor'
        ];
    }
}
