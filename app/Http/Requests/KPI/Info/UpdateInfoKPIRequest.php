<?php

namespace App\Http\Requests\KPI\Info;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInfoKPIRequest extends FormRequest
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
            'ID' => 'required|unique:InfoKpi,ID,'.$this->segment(3),
            'Judul' => 'required',
            'Tanggal_publish' => 'required|date_format:Y-m-d',
            'Tanggal_berakhir' => 'nullable|date_format:Y-m-d',
            'Gambar' => 'nullable|image|max:500',
            'Informasi' => 'required',
            'User_id' => 'required|exists:Users,ID',
        ];
    }
}
