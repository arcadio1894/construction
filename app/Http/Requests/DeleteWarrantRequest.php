<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteWarrantRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'warrant_id.required' => 'El :attribute es obligatorio.',
            'warrant_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'warrant_id' => 'id de la cédula de material'
        ];
    }
}
