<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadStockFilesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|mimes:xlsx,xls|max:10240', // Limite de 2MB
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'El archivo es obligatorio.',
            'file.mimes' => 'Solo se permiten archivos de Excel (.xlsx, .xls).',
            'file.max' => 'El tamaño del archivo no debe exceder los 10MB.',
        ];
    }
}
