<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 10/26/2017
 * Time: 11:44 PM
 */

namespace App\Http\Requests\KPI\Realisasi;

use Illuminate\Foundation\Http\FormRequest;

class ReportRecapitulationRequest extends FormRequest
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
            'Tahun'=> 'required',
            'IDJenisPeriode'=>'required'
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
            'Tahun.required' => 'Tahun belum dipilih',
            'IDJenisPeriode.required' => 'Periode KPI belum dipilih'
        ];
    }
}
