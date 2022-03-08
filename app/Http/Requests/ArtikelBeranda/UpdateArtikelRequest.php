<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 09/13/2017
 * Time: 10:21 PM
 */

namespace App\Http\Requests\ArtikelBeranda;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArtikelRequest extends FormRequest
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
            'Title' => 'sometimes|max:50',//required|max:50
            'Content'=> 'required',//|max:400
            'Aktif'=> 'sometimes|required'
        ];
    }
    /*
     *
     */
    public function messages()
    {
        return [
            //'Title.required' => 'Judul harus diisi',
            'Title.max' => 'Judul memiliki panjang max 50 huruf',
            'Content.required' => 'Konten harus diisi',
            //'Content.max' => 'Konten memiliki panjang max 400 huruf',
            'Aktif.required' => 'Aktif harus diisi'
        ];
    }
}
