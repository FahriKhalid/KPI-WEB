<?php

namespace App\Http\Requests\Master\PeriodeRealisasi;

use Illuminate\Foundation\Http\FormRequest;

class StorePeriodeRealisasiRequest extends FormRequest
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
            'Tahun' => 'required',
            'IDJenisPeriode' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'Tahun.required' => 'Tahun required',
            'IDJenisPeriode.required' => 'ID Jenis Periode required'
        ];
    }
}
