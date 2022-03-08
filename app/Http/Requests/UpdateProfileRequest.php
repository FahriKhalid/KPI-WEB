<?php
/**
 * Created by PhpStorm.
 * User: SENA
 * Date: 09/08/2017
 * Time: 07:43 AM
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'personal' => 'present',
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'personal.present'=>'Acces Denied: Not Personal',
            'old_password.required' => 'Password lama harus diisi',
            'password.min' => 'Password minimal harus 6 karakter',
            'password.required' => 'Password baru harus diisi',
            'password.confirmed' => 'Konfirmasi password tidak sama'
        ];
    }
}
