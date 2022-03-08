<?php

namespace App\Http\Requests\KPI\Rencana;

use Illuminate\Foundation\Http\FormRequest;

class UnapproveRencanaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->check()) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'CatatanUnapprove' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
          'CatatanUnapprove.required' => 'Catatan Unapprove harus diisi'
        ];
    }
}
