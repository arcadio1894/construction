<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeletePaymentDeadlineRequest extends FormRequest
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
            'paymentDeadline_id' => 'required|exists:payment_deadlines,id',
        ];
    }

    public function messages()
    {
        return [
            'paymentDeadline_id.required' => 'El :attribute es obligatorio.',
            'paymentDeadline_id.exists' => 'El :attribute no existe en la base de datos.'
        ];
    }

    public function attributes()
    {
        return [
            'paymentDeadline_id' => 'id del plazo de pago'
        ];
    }
}
