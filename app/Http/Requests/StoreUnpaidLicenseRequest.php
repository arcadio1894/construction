<?php

namespace App\Http\Requests;
use App\UnpaidLicense;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUnpaidLicenseRequest extends FormRequest
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
            'reason' => 'required|string|max:100',
            'date_start' => [
                'required',
                'date_format:d/m/Y',
                'after:01/01/' . (date('Y') - 1),
                /*function ($value, $fail) {
                    $this->validateDateRange(Carbon::createFromFormat('d/m/Y', $this->input('date_start')), $fail);
                },*/
            ],
            'date_end' => [
                'required',
                'date_format:d/m/Y',
                'after:date_start',
                /*function ($value, $fail) {
                    $this->validateDateRange(Carbon::createFromFormat('d/m/Y', $this->input('date_start')), $fail);
                },*/
            ],
            'worker_id' => 'required|exists:workers,id',
            'file' => 'file|mimes:jpg,png,pdf|max:10240',
        ];
    }

    public function messages()
    {
        return [
            'reason.required' => 'El :attribute es obligatorio.',
            'reason.max' => 'El :attribute no puede ser mayor a :max caracteres.',
            'date_start.required' => 'La :attribute es obligatoria.',
            'date_start.date_format' => 'La :attribute debe tener el formato dd/mm/yyyy.',
            'date_start.after' => 'El año de la :attribute es incorrecto',
            'date_end.required' => 'La :attribute es obligatoria.',
            'date_end.date_format' => 'La :attribute debe tener el formato dd/mm/yyyy.',
            'date_end.after' => 'La :attribute debe ser igual o posterior a la fecha de inicio.',
            'date_end.custom_rule' => 'La :attribute debe estar fuera de las fechas de asistencia del trabajador.',
            'worker_id.required' => 'El :attribute es obligatorio.',
            'worker_id.exists' => 'El :attribute seleccionado no es válido.',
            'file.mimes' => 'El :attribute debe ser una imagen (jpeg, jpg, png) o un PDF.',
            'file.max' => 'El :attribute no debe ser mayor a :max kilobytes.',
        ];
    }

    public function attributes()
    {
        return [
            'reason' => 'motivo',
            'date_start' => 'fecha inicio',
            'date_end' => 'fecha fin',
            'worker_id' => 'trabajador',
            'file' => 'archivo',
        ];
    }

    private function validateDateRange($date, $fail)
    {
        $workerId = $this->input('worker_id');

            $existingLicense = UnpaidLicense::where('worker_id',$workerId)
                ->whereDate('date_start', '<=', $date)
                ->whereDate('date_end', '>=', $date)
                ->get();
            if (count($existingLicense)>0) {
                $fail("El :attribute está dentro del rango de una licencia ya existente.");
            }

    }
}
