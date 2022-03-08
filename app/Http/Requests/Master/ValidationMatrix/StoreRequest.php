<?php

namespace App\Http\Requests\Master\ValidationMatrix;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'KodeUnitKerja' => 'required',
            'KodeUnitKerjaPenilai' => 'required|different:KodeUnitKerja'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'KodeUnitKerja.required' => 'Unit Kerja harus diisi.',
            'KodeUnitKerjaPenilai.required' => 'Unit Kerja penilai harus diisi.',
            'KodeUnitKerjaPenilai.different' => 'Unit Kerja penilai tidak boleh sama dengan unit kerja yang dinilai.',
        ];
    }
}
