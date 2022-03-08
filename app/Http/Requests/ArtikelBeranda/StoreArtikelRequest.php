<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 09/13/2017
 * Time: 10:20 PM
 */

namespace App\Http\Requests\ArtikelBeranda;

use Illuminate\Foundation\Http\FormRequest;

class StoreArtikelRequest extends FormRequest
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
            'Content'=> 'nullable',
        ];
    }
}
