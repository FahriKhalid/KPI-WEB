<?php

namespace App\Http\Requests\Master\PeriodeAktif;

use Auth;
use App\Http\Requests\Request;

class Validator extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::check()) {
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
            'ID' => 'required',
            'NamaPeriode' => 'required',
            'Tahun' => 'Tahun',
            'StartDate' => 'required',
            'EndDate' => 'required',
            'StatusPeriode' => 'required',
            'Aktif' => 'required',
            'Keterangan' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'ID.required' => 'ID autoincrement',
            'NamaPeriode.required' => 'NamaPeriode harus diisi untuk mengetahui periode KPI dalam kurn waktu satu tahun',
            'Tahun.required' => 'Periode tahun harus diisi',
            'StartDate.required' => 'Mulai aktif harus diisi',
            'EndDate.required' => 'Berakhir aktif harus diisi',
            'StatusPeriode.required' => 'Status periode harus diisi',
            'Aktif.required' => 'Ke aktifan KPI harus diisi',
            'Keterangan.required' => 'Keterangan dsri KPI periode aktif harus diisi'
        ];
    }
}
