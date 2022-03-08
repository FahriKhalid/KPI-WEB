<?php

namespace App\Http\Requests\Master\UnitKerja;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUnitKerjaRequest extends FormRequest
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
     * //'unique:namatable,namakolom,valuerequest|required'
     * @return array
     */
    public function rules()
    {
        return [
            'CostCenter' => 'sometimes|unique:Ms_UnitKerja,CostCenter,'.$this->CostCenter.',CostCenter|required',
            'Deskripsi' => 'required',
            'Keterangan' => 'bail|nullable',
            'Aktif' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'CostCenter.unique'=>'Kode unit kerja sudah tersedia dalam database',
            'CostCenter.required'=>'Kode unit kerja harus diisi',
            'Deskripsi.required'=>'Nama unit kerja harus diisi',
            'Keterangan.bail'=>'Masalah dalam kolom keterangan',
            'Aktif.required'=>'Status harus diisi'
        ];
    }
}
