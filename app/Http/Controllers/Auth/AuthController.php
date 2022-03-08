<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ApplicationServices\Authentication\UserAuthenticationService;
use App\ApplicationServices\Master\User\UpdateUser;
use App\Http\Controllers\Controller;
use App\Domain\User\Entities\User; 
use Session;

class AuthController extends Controller
{
    /**
     * @var UserAuthenticationService
     */
    protected $authService;

    
    /**
     * AuthController constructor.
     *
     * @param UserAuthenticationService $userAuthenticationService
     */
    public function __construct(UserAuthenticationService $userAuthenticationService)
    {
        $this->authService = $userAuthenticationService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
 
        return view('auth.login');
    }

    /**
     * @param Request $request
     * @param UpdateUser $updateUser
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request, UpdateUser $updateUser)
    {
        $result = $this->authService->attempt($request->only(['NPK', 'password']));
        if ($result['status']) { 
            // $updateUser->activatePrivilege(Auth::user()->ID, ['IDRole'=>'3']);
            return redirect()->intended($this->authService->getRedirectUrl());
        }
        flash($result['errors'], 'danger')->important();
        return redirect()->back();
    }

    /**
     * @param $id
     * @param Request $request
     * @param UpdateUser $updateUser
     * @return \Illuminate\Http\RedirectResponse
     */
    public function privilege($id, Request $request, UpdateUser $updateUser)
    {
        $result = $updateUser->activatePrivilege($id, $request->only('IDRole'));
        if ($result['status']) {
            flash()->success('Berhasil beralih role')->important();
            return redirect()->route('dashboard');
        }
        flash()->error($result['errors'])->important();
        return redirect()->back();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
     public function logout()
     {

         $result = $this->authService->logout();
         if ($result['status']) {
             flash('Anda telah berhasil logout', 'success')->important();
             return redirect()->route($this->authService->getLoginRoute());
         }
         flash('Logout gagal. '.$result['errors'], 'danger')->important();
         return redirect()->back();
     }


    public function share_session(Request $request)
    {
        $user = User::where('npk', $request->npk)->orWhere('npk_email', $request->npk)->first();
        Auth::loginUsingId($user->id);
        return ['body' => Auth::loginUsingId($user->id)];
    }
}
