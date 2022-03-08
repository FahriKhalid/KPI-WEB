<?php

namespace App\Http\Requests\Master\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'NPK' => [
                'required',
                'exists:Ms_Karyawan',
                Rule::unique('Users')->ignore(request()->segment(3))
            ],
            'username' => 'required',
            'password' => 'nullable|min:6|confirmed'
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
            'NPK.unique' => 'NPK yang diisi sudah didaftarkan sebelumnya',
            'password.min' => 'Password minimal harus 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sama'
        ];
    }
}
