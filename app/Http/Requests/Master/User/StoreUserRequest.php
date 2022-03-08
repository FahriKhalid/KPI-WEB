<?php

namespace App\Http\Requests\Master\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'IDRole' => 'exists:UserRoles,ID',
            'NPK' => 'required|exists:Ms_Karyawan|unique:Users',
            'username' => 'required',
            'password' => 'required|min:6|confirmed'
        ];
    }
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'IDRole.exists' => 'Role yang anda pilih tidak tersedia.',
            'username.required' => 'NPK lama harus diisi.',
            'NPK.required' => 'NPK harus diisi',
            'NPK.exists' => 'NPK yang diisi tidak tersedia',
            'NPK.unique' => 'NPK yang diisi sudah didaftarkan sebelumnya.',
            'password.min' => 'Password minimal harus 6 karakter',
            'password.required' => 'Password harus diisi',
            'password.confirmed' => 'Konfirmasi password tidak sama'
        ];
    }
}
