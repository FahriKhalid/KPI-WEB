<?php

namespace App\Http\Requests\KPI\Rencana;

use Illuminate\Foundation\Http\FormRequest;

class StoreDetailRencanaRequest extends FormRequest
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
        $regex = "/^(?:\d{1,15}|\d{1,15}\.\d{1,2})$/";
        $ruleTarget = ['sometimes','required','numeric','regex:'.$regex]; 
             
        if(\Auth::user()->IDRole == 8)
        {
            if ($this->get('IDKodeAspekKPI') == 2) { // rutin struktural
                $ruleBobot = '|min:2.5|max:5';
            } elseif ($this->get('IDKodeAspekKPI') == 4) { // task force
                $ruleBobot = '|min:2|max:2';
            } else {
                $ruleBobot = '|min:2.5|max:20';
            }
        }
        else
        {
            // validate min max bobot
            if ($this->get('IDKodeAspekKPI') == 2) { // rutin struktural
                $ruleBobot = '|min:5|max:5';
            } elseif ($this->get('IDKodeAspekKPI') == 4) { // task force
                $ruleBobot = '|min:2|max:2';
            } else {
                $ruleBobot = '|min:5|max:20';
            }
        }

        // validate min max bobot
        // if ($this->get('IDKodeAspekKPI') == 2) { // rutin struktural
        //     $ruleBobot = '|min:5|max:5';
        // } elseif ($this->get('IDKodeAspekKPI') == 4) { // task force
        //     if(\Auth::user()->IDRole == 8){

        //     }else{
        //         $ruleBobot = '|min:2|max:2';
        //     }
        // } else {
        //     if(\Auth::user()->IDRole == 8){
        //         $ruleBobot = '|min:2.5|max:20';
        //     }else{
        //         $ruleBobot = '|min:5|max:20';
        //     } 
        // }

        

        return [
            'KodeRegistrasiKamus' => 'nullable',
            'IDKodeAspekKPI' => 'required|exists:VL_AspekKPI,ID',
            'IDJenisAppraisal' => 'required|exists:VL_JenisAppraisal,ID',
            'IDPersentaseRealisasi' => 'required|exists:VL_PersentaseRealisasi,ID',
            'DeskripsiKPI' => 'required',
            'IDJenisPeriode' => 'required|exists:VL_JenisPeriode,ID',
            'Target1' =>  $ruleTarget,
            'Target2' =>  $ruleTarget,
            'Target3' =>  $ruleTarget,
            'Target4' =>  $ruleTarget,
            'Target5' =>  $ruleTarget,
            'Target6' =>  $ruleTarget,
            'Target7' =>  $ruleTarget,
            'Target8' =>  $ruleTarget,
            'Target9' =>  $ruleTarget,
            'Target10' =>  $ruleTarget,
            'Target11' =>  $ruleTarget,
            'Target12' =>  $ruleTarget,
            'IDSatuan' => 'required|exists:VL_Satuan,ID',
            'Bobot' => 'required|numeric'.$ruleBobot,
            //'IsKRABawahan' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            //'KodeRegistrasiKamus.required'=>'Kamus Belum dipilih',
            'IDKodeAspekKPI.required'=>'Aspek harus dipilih',
            'IDJenisAppraisal.required'=>'Jenis appraisal harus dipilih',
            'IDPersentaseRealisasi.required'=>'Presentasi realisasi harus dipilih',
            'DeskripsiKPI.required'=>'Deskripsi KPI harus diisi',
            'IDJenisPeriode.required'=>'Jenis periode harus dipilih',
            'Target1.required'=>'Target 1 harus diisi',
            'Target1.numeric'=>'Target 1 harus berupa angka',
            'Target1.regex'=>'Target 1 harus berupa angka <i><b>\'valid\'</b></i>, digit (maks:15) dan dibelakang koma (maks:2)',
            'Target2.required'=>'Target 2 harus diisi',
            'Target2.numeric'=>'Target 2 harus berupa angka',
            'Target2.regex'=>'Target 2 harus berupa angka <i><b>\'valid\'</b></i>, digit (maks:15) dan dibelakang koma (maks:2)',
            'Target3.required'=>'Target 3 harus diisi',
            'Target3.numeric'=>'Target 3 harus berupa angka',
            'Target3.regex'=>'Target 3 harus berupa angka <i><b>\'valid\'</b></i>, digit (maks:15) dan dibelakang koma (maks:2)',
            'Target4.required'=>'Target 4 harus diisi',
            'Target4.numeric'=>'Target 4 harus berupa angka',
            'Target4.regex'=>'Target 4 harus berupa angka <i><b>\'valid\'</b></i>, digit (maks:15) dan dibelakang koma (maks:2)',
            'Target5.required'=>'Target 5 harus diisi',
            'Target5.numeric'=>'Target 5 harus berupa angka',
            'Target5.regex'=>'Target 5 harus berupa angka <i><b>\'valid\'</b></i>, digit (maks:15) dan dibelakang koma (maks:2)',
            'Target6.required'=>'Target 6 harus diisi',
            'Target6.numeric'=>'Target 6 harus berupa angka',
            'Target6.regex'=>'Target 6 harus berupa angka <i><b>\'valid\'</b></i>, digit (maks:15) dan dibelakang koma (maks:2)',
            'Target7.required'=>'Target 7 harus diisi',
            'Target7.numeric'=>'Target 7 harus berupa angka',
            'Target7.regex'=>'Target 7 harus berupa angka <i><b>\'valid\'</b></i>, digit (maks:15) dan dibelakang koma (maks:2)',
            'Target8.required'=>'Target 8 harus diisi',
            'Target8.numeric'=>'Target 8 harus berupa angka',
            'Target8.regex'=>'Target 8 harus berupa angka <i><b>\'valid\'</b></i>, digit (maks:15) dan dibelakang koma (maks:2)',
            'Target9.required'=>'Target 9 harus diisi',
            'Target9.numeric'=>'Target 9 harus berupa angka',
            'Target9.regex'=>'Target 9 harus berupa angka <i><b>\'valid\'</b></i>, digit (maks:15) dan dibelakang koma (maks:2)',
            'Target10.required'=>'Target 10 harus diisi',
            'Target10.numeric'=>'Target 10 harus berupa angka',
            'Target10.regex'=>'Target 10 harus berupa angka <i><b>\'valid\'</b></i>, digit (maks:15) dan dibelakang koma (maks:2)',
            'Target11.required'=>'Target 11 harus diisi',
            'Target11.numeric'=>'Target 11 harus berupa angka',
            'Target11.regex'=>'Target 11 harus berupa angka <i><b>\'valid\'</b></i>, digit (maks:15) dan dibelakang koma (maks:2)',
            'Target12.required'=>'Target 12 harus diisi',
            'Target12.numeric'=>'Target 12 harus berupa angka',
            'Target12.regex'=>'Target 12 harus berupa angka <i><b>\'valid\'</b></i>, digit (maks:15) dan dibelakang koma (maks:2)',
            'IDSatuan.required'=>'Satuan harus dipilih',
            'Bobot.required'=>'Bobot harus diisi',
            'Bobot.min' => 'Bobot minimal harus :min %',
            'Bobot.max' => 'Bobot maksimal harus :max %',
            'Bobot.numeric' => 'Bobot harus berupa numerik',
            //'IsKRABawahan.required'=>'Pernyataan sebagai KPI Bawahan harus diisi',
        ];
    }
}
