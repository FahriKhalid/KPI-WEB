<?php

namespace App\Http\Requests\KPI\Validasi;

use Illuminate\Foundation\Http\FormRequest;

class StoreValidasiRequest extends FormRequest
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
            'IDKPIRealisasiHeader' => 'required',
            'ValidasiUnitKerja' => 'required|in:95,100,105',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'IDKPIRealisasiHeader.required' => 'ID KPI Header Realisasi dibutuhkan.',
            'ValidasiUnitKerja.required' => 'Nilai Validasi Unit Kerja harus dipilih.',
            'ValidasiUnitKerja.in' => 'Nilai Validasi Unit Kerja harus bernilai 95% atau 100% atau 105%.'
        ];
    }
}
