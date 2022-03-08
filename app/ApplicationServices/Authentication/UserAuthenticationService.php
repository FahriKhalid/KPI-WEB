<?php
namespace App\ApplicationServices\Authentication;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\ApplicationServices\ApplicationService;
use App\Domain\User\Entities\User; 

class UserAuthenticationService extends ApplicationService
{
    /**
     * @var string
     */
    protected $redirectTo = 'dashboard';

    /**
     * @var string
     */
    protected $loginRoute = 'login';

    /**
     * @var AuthenticationContract
     */
    protected $auth;

    public function username()
    {
        return 'npk';
    }

    /**
     * @param null $adapter
     */
    protected function setAdapter($adapter = null)
    { 
        if ($adapter == 'ldap') {
            $this->auth = new LDAPAuthentication();
        } else {
            $this->auth = new FacadeAuthentication();
        }
    }

    /**
     * @param $credential
     * @return array|mixed
     */
    public function attempt($credential)
    {
        $user = User::where('NPK', $credential['NPK'])->orWhere('username', $credential['NPK'])->first();

        if ($user) {
            // $adapter = ($user->Ldap_active == 1) ? 'ldap' : 'facade';
            // $this->setAdapter($adapter); 
            // return $this->auth->attempt($credential);

            if( Auth::login($user)) {
                return $this->successResponse();
            }
        }
        return $this->errorResponse('Karyawan atau pengguna belum terdaftar pada sistem KPI Online.');
    }

    /**
     * @return array
     */
    public function logout()
    {
        try {
            // $user = \auth()->user();
            // $user->isLogin=false;
            // $user->save();

            Auth::logout();
            Session::flush();
            return $this->successResponse();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectTo;
    }

    /**
     * @return string
     */
    public function getLoginRoute()
    {
        return $this->loginRoute;
    }
}
