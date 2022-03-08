<?php
namespace App\ApplicationServices\Authentication;

use Illuminate\Support\Facades\Auth;
use App\ApplicationServices\ApplicationService;

class FacadeAuthentication extends ApplicationService implements AuthenticationContract
{
    /**
     * @param $credential
     * @return array
     */
    public function attempt($credential)
    {  

        if (Auth::attempt(['npk' => $credential['NPK'], 'password' => $credential['password']])) { 
            return $this->successResponse();
        }
        return $this->errorResponse('NPK atau password yang Anda masukkan salah.');
    }
}
