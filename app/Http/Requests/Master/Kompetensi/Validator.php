<?php

namespace App\Http\Requests\Master\Kompetensi;

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
            'PositionID' => 'required',
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
            'PositionID.required' => 'PositionID harus diisi untuk mengetahui posisinya',
            'Keterangan.required' => 'Keterangan dsri KPI periode aktif harus diisi'
        ];
    }
}
