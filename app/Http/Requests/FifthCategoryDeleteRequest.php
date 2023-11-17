<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FifthCategoryDeleteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'worker_id' => 'required|exists:workers,id',
            'fifthCategory_id' => 'required|exists:fifth_categories,id',
        ];
    }

    public function messages()
    {
        return [
            'worker_id.required' => 'El :attribute es obligatorio.',
            'worker_id.exists' => 'El :attribute no existe en la base de datos.',
            'fifthCategory_id.required' => 'El :attribute es obligatorio.',
            'fifthCategory_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'worker_id' => 'trabajador',
            'fifthCategory_id' => 'id del pago',
        ];
    }
}
