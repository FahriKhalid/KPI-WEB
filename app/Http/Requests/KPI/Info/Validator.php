<?php

namespace App\Http\Requests\KPI\Info;

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
            'Judul' => 'required',
            'Informasi' => 'required',
            'Gambar' => 'nullable|image|max:500',
            'Tanggal_publish' => 'required|date_format:Y-m-d H:i:s',
            'Tanggal_berakhir' => 'nullable|date_format:Y-m-d H:i:s'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'Judul.required'  => 'Judul harus diisi',
            'Informasi.required' => 'Informasi harus diisi',
            'Tanggal_publish.required' => 'Tanggal publish harus diisi',
            'Tanggal_publish.date_format' => 'Format tanggal publish harus Y-m-d H:i:s',
            'Tanggal_berakhir.date_format' => 'Format tanggal berakhir harus Y-m-d H:i:s',
            'Gambar.image' => 'File harus berupa gambar'
        ];
    }
}
