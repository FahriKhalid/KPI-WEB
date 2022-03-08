<?php

namespace App\Http\Requests\KPI\Rencana;

use Illuminate\Foundation\Http\FormRequest;

class StoreHeaderRequest extends FormRequest
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
            'Tahun' => 'required|exists:Ms_PeriodeAktif,Tahun',
            'NPK' => 'required|exists:Ms_Karyawan',
            'KodeUnitKerja' => 'required',
            'IDMasterPosition' => 'required',
            'Grade' => 'required',
            'NamaKaryawan' => 'required',
            'NPKAtasanLangsung' => 'required|exists:Ms_Karyawan,NPK',
            'NamaAtasanLangsung' => 'required',
            'JabatanAtasanLangsung' => 'required',
            'NPKAtasanBerikutnya' => 'required|exists:Ms_Karyawan,NPK',
            'NamaAtasanBerikutnya' => 'required',
            'JabatanAtasanBerikutnya' => 'required'
        ];
    }

    /**
     *
     *
     * @return array
     */
    public function messages()
    {
        return [
            'Tahun.required' => 'Tahun harus diisi',
            'Tahun.exists' => 'Tahun belum terdaftar dalam periode aktif',
            'NPK.required' => 'NPK harus diisi',
            'NPK.exists' => 'NPK belum ada atau terdaftar',
            'KodeUnitKerja.required' => 'Kode unit kerja harus diisi',
            'IDMasterPosition.required' => 'ID master position harus diisi',
            'Grade.required' => 'Grade harus diisi',
            'NamaKaryawan.required' => 'Nama karyawan harus diisi',
            'NPKAtasanLangsung.required' => 'NPK atasan langsung belum ada atau terdaftar',
            'NPKAtasanLangsung.exists' => 'NPK atasan langsung belum ada atau terdaftar',
            'NamaAtasanLangsung.required' => 'Nama atasan langsung harus diisi',
            'JabatanAtasanLangsung.required' => 'Jabatan atasan langsung harus diisi',
            'NPKAtasanBerikutnya.required' => 'NPK atasan berikutnya belum ada atau terdaftar',
            'NPKAtasanBerikutnya.exists' => 'NPK atasan berikutnya belum ada atau terdaftar',
            'NamaAtasanBerikutnya.required' => 'Nama atasan berikutnya harus diisi',
            'JabatanAtasanBerikutnya.required' => 'Jabatan atasan berikutnya harus diisi'
        ];
    }
}
