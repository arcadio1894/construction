<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermitHourStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'reason' => 'required|string|max:255',
            'date_start' => 'required|date_format:d/m/Y',
            'hour' => 'required|numeric',
            'worker_id' => 'required|exists:workers,id',
        ];
    }
    public function messages()
    {
        return [
            'reason.required' => 'El :attribute es obligatorio.',
            'reason.string' => 'El :attribute debe ser una cadena de caracteres.',
            'reason.max' => 'El :attribute no debe superar :max caracteres.',
            'date_start.required' => 'La :attribute es obligatoria.',
            'date_start.date_format' => 'La :attribute debe estar en formato día/mes/año (dd/mm/yyyy).',
            'hour.required' => 'La :attribute es obligatoria.',
            'hour.numeric' => 'La :attribute debe ser un numero de 4 unidades',
            'worker_id.required' => 'El :attribute es obligatorio.',
            'worker_id.exists' => 'El :attribute seleccionado no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'reason' => 'motivo',
            'date_start' => 'fecha de inicio',
            'hour' => 'hora',
            'worker_id' => 'trabajador'
        ];
    }


}
