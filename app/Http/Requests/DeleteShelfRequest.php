<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteShelfRequest extends FormRequest
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
            'shelf_id' => 'required|exists:shelves,id',
        ];
    }

    public function messages()
    {
        return [
            'shelf_id.required' => 'El :attribute es obligatorio.',
            'shelf_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'shelf_id' => 'id del anaquel'
        ];
    }
}
