<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 08/31/2017
 * Time: 06:03 AM
 */

namespace App\Http\Requests\KPI\Realisasi;

use Illuminate\Foundation\Http\FormRequest;

class GrafikNilaiRequest extends FormRequest
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
        $ruleBawahan = '';
        if ($this->get('IsBawahan')==true && $this->get('NPK')==null) {
            $ruleBawahan = '|required';
        }
        return [
            'NPK'=> 'sometimes'.$ruleBawahan,
            'Tahun'=>'required'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'NPK.required' => 'Bawahan belum dipilih',
            'Tahun.required' => 'Tahun periode belum dipilih'
        ];
    }
}
