<?php

namespace App\Http\Requests\KPI\Rencana;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
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
            'Caption' => 'required',
            'File' => 'required|max:5000'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
          'Caption.required' => 'Caption harus diisi',
          'File.required' => 'Berkas harus diisi',
          'File.max' => 'Ukuran berkas maksimal adalah 5MB'
        ];
    }
}
