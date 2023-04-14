<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FifthCategoryWorkerStoreRequest extends FormRequest
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

    public function rules()
    {
        return [
            'worker' => 'required|exists:workers,id',
        ];
    }

    public function messages()
    {
        return [
            'worker_id.required' => 'El :attribute es obligatorio.',
            'worker_id.exists' => 'El :attribute no existe en la base de datos.',
        ];
    }

    public function attributes()
    {
        return [
            'worker_id' => 'trabajador',
        ];
    }
}
