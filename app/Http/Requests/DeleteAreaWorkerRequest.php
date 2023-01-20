<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteAreaWorkerRequest extends FormRequest
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
            'areaWorker_id' => 'required|exists:area_workers,id',
        ];
    }

    public function messages()
    {
        return [
            'areaWorker_id.required' => 'El :attribute es obligatorio.',
            'areaWorker_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'areaWorker_id' => 'id del área'
        ];
    }
}
