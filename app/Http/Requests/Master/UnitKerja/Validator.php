<?php

namespace App\Http\Requests\Master\UnitKerja;

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
            'Deskripsi' => 'required',
            'Keterangan' => 'required',
            'Aktif' => 'required',
            'Field1' => 'required',
            'Field2' => 'required',
            'Field3' => 'required',
            'Field4' => 'required',
            'Field5' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'Deskripsi.required' => 'Deskripsi harus diisi',
            'Keterangan.required' => 'Keterangan (opsional)',
            'Aktif.required' => 'Keaktifan unit kerja harus diisi',
            'Field1.required' => 'field1 harus diisi',
            'Field2.required' => 'field2 harus diisi',
            'Field3.required' => 'field3 harus diisi',
            'Field4.required' => 'field4 harus diisi',
            'Field5.required' => 'field5 harus diisi'
        ];
    }
}
