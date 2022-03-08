<?php

namespace App\Http\Requests\KPI\Rencana;

use Illuminate\Foundation\Http\FormRequest;

class StorePenurunanRencanaRequest extends FormRequest
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
            'IDKPIAtasan' => 'required|exists:Tr_KPIRencanaDetil,ID',
            'NPKBawahan' => 'required|exists:Ms_Karyawan,NPK',
            'PersentaseKRA' => 'required|numeric|max:100'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'IDKPIAtasan.required' => 'KPI Atasan harus dipilih.',
            'NPKBawahan.required' => 'Bawahan harus dipilih.',
            'PersentaseKRA.required' => 'Persentase KRA harus diisi.',
            'PersentaseKRA.max' => 'Persentase KRA tidak boleh lebih dari 100%.'
        ];
    }
}
