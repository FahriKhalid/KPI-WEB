<?php

namespace App\Http\Requests\Master\PeriodeAktif;

use Illuminate\Foundation\Http\FormRequest;

class StorePeriodeAktifRequest extends FormRequest
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
            //'ID' => 'required|unique:Ms_PeriodeAktif',
            'NamaPeriode' => 'required',
            'Tahun' => 'required',
            'StatusPeriode' => 'nullable',
            'Aktif' => 'nullable',
            'Keterangan' => 'nullable',
        ];
    }
}
