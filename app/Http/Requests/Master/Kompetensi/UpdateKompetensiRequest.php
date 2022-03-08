<?php

namespace App\Http\Requests\Master\Kompetensi;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKompetensiRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'PositionID' => 'required',
            'Keterangan' => 'required',
        ];
    }
}
