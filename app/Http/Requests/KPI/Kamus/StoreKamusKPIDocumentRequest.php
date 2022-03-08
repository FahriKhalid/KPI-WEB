<?php

namespace App\Http\Requests\KPI\Kamus;

use Illuminate\Foundation\Http\FormRequest;

class StoreKamusKPIDocumentRequest extends FormRequest
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
            'Excel'=>'bail|required|mimes:xls,xlsx',/*|mimetypes:application/excel,
            application/vnd.ms-excel*/
        ];
    }
    public function messages()
    {
        return [
            'Excel.required' =>'Data Excel kosong',
            'Excel.mimes' =>'Dokumen bukan ekstensi excel',
            // 'Excel.mimetypes' =>'Dokumen bukan ekstensi excel'
        ];
    }
}
