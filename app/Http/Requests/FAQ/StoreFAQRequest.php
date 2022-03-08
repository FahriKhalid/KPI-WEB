<?php

namespace App\Http\Requests\FAQ;

use Illuminate\Foundation\Http\FormRequest;

class StoreFAQRequest extends FormRequest
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
            'Question' => 'required|max:255',
            'Answer'=> 'sometimes|required',
            'Aktif'=> 'sometimes|required'
        ];
    }
    /*
     *
     */
    public function messages()
    {
        return [
            'Question.required' => 'Pertanyaan harus diisi',
            'Question.max' => 'Pertanyaan memiliki panjang max 255',
            'Answer.required' => 'Jawaban harus diisi',
            'Aktif.required' => 'Aktif harus diisi'
        ];
    }
}
