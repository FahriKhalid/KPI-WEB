<?php

namespace App\Http\Requests\KPI\Kamus;

use Illuminate\Foundation\Http\FormRequest;

class StoreKamusKPIRequest extends FormRequest
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
            'KodeRegistrasi' => (($this->user()->UserRole->Role == 'Administrator') ? 'required|' : '').'unique:Ms_KamusKPI,KodeRegistrasi',
            'KodeUnitKerja' => 'required|exists:Ms_UnitKerja,CostCenter',
            'IDAspekKPI' => 'required|exists:VL_AspekKPI,ID',
            'IDJenisAppraisal' => 'required|exists:VL_JenisAppraisal,ID',
            'IDSatuan' => 'required|exists:VL_Satuan,ID',
            'IDPersentaseRealisasi' => 'required|exists:VL_PersentaseRealisasi,ID',
            'KPI' => 'required|max:255',
            'Deskripsi' => 'required',
            'IndikatorHasil' => 'sometimes|max:255',
            'IndikatorKinerja' => 'sometimes|max:255',
            'SumberData' => 'sometimes|max:255',
            'Jenis' => 'sometimes|max:255'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'KodeRegistrasi.required' => 'Kode registrasi harus diisi',
            'KodeRegistrasi.unique' => 'Kode registrasi sudah pernah ada',
            'KodeUnitKerja.required' => 'Kode unit kerja harus diisi',
            'KodeUnitKerja.exists' => 'Kode unit kerja belum terdaftar',
            'IDAspekKPI.required' => 'Aspek KPI harus diisi',
            'IDAspekKPI.exists' => 'Aspek KPI belum terdaftar',
            'IDJenisAppraisal.required' => 'Jenis appraisal harus diisi',
            'IDJenisAppraisal.exists' => 'Jenis appraisal belum terdaftar',
            'IDSatuan.required' => 'Satuan harus diisi',
            'IDSatuan.exists' => 'Satuan belum terdaftar',
            'IDPersentaseRealisasi.required' => 'Persentase realisasi harus diisi',
            'IDPersentaseRealisasi.exists' => 'Persentase realisasi belum terdaftar',
            'KPI.required' => 'Judul KPI harus diisi',
            'KPI.max' => 'Judul KPI panjang max: 255',
            'Deskripsi.required' => 'Deskripsi harus diisi',
            'IndikatorHasil.max' => 'Indikator hasil panjang max: 255',
            'IndikatorKinerja.max' => 'Indikator kinerja panjang max: 255',
            'SumberData.max' => 'Sumber data panjang max: 255',
            'Jenis.max' => 'Jenis panjang max: 255'
        ];
    }
}
