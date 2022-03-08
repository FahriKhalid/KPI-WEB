<?php
namespace App\ApplicationServices\Authentication;

use Illuminate\Support\Facades\Auth;
use App\ApplicationServices\ApplicationService;
use App\Domain\User\Entities\User;

class LDAPAuthentication extends ApplicationService implements AuthenticationContract
{
    /**
     * @param $credential
     * @return array|bool
     */
    public function attempt($credential)
    {
        $ldap_user  = $credential['NPK'];
        $ldap_paswd = $credential['password'];
        $ldap_server = config('auth.ldapHost');

        if ($ldap_conn = ldap_connect($ldap_server)) {
            $ldap_bind = @ldap_bind($ldap_conn, $ldap_user, $ldap_paswd);

            if ($ldap_bind) {
                $user = User::NpkLama($ldap_user)->first();
                if (! is_null($user)) {
                    Auth::login($user);
                    return $this->successResponse();
                }
                return $this->errorResponse('Karyawan belum terdaftar pada sistem KPI Online.');
            }
            @ldap_close($ldap_conn);
            return $this->errorResponse('NPK atau kata sandi yang anda masukkan pada LDAP salah.');
        }
        return $this->errorResponse('Error connecting LDAP server');
    }
}
