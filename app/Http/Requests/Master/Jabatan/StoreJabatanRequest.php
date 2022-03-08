<?php 

namespace App\Http\Requests\Master\Jabatan;

use Illuminate\Foundation\Http\FormRequest;

/**
 * summary
 */
class StoreJabatanRequest extends FormRequest
{
    /**
     * summary
     */
    public function authorize()
    {
        if (auth()->check()) {
            return true;
        }
        return false;
    }
    /**
     * summary
     */
    public function rules()
    {
        return [
            'PositionID' => 'required|max:8',
            'PositionTitle' => 'required|max:50',
            'PositionAbbreviation' => 'required|size:12',
            'KodeUnitKerja' => 'required',
            'StatusAktif' => 'required',
            'Keterangan' => 'max:255'
        ];
    }
    /**
     * summary
     */
    public function messages()
    {
        return [
            'PositionID.required' => 'ID posisi harus diisi',
            'PositionID.max' => 'ID terlalu panjang (max:8)',
            'PositionTitle.required' => 'Judul posisi harus diisi',
            'PositionTitle.max' => 'Judul posisi terlalu panjang (max:50)',
            'PositionAbbreviation.required' => 'Inisial harus diisi',
            'PositionAbbreviation.size' => 'Panjang posisi abbreviation harus 12',
            'KodeUnitKerja.required' => 'Kode unit kerja harus diisi',
            'StatusAktif.required' => 'Status aktif invalid',
            'Keterangan.max' => 'Keterangan terlalu panjang'
        ];
    }
}
