<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAreaWorkerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'areaWorker_id.required' => 'El :attribute es obligatorio.',
            'areaWorker_id.exists' => 'El :attribute debe existir en la base de datos.',
            'name.required' => 'El :attribute es obligatorio.',
            'name.string' => 'El :attribute debe contener caracteres válidos.',
            'name.max' => 'El :attribute debe contener máximo 255 caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'areaWorker_id' => 'id del área',
            'name' => 'nombre del área',
        ];
    }
}
