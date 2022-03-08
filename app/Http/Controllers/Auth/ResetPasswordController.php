<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Repositories\Setting\InformasiRepository;
use App\Http\Requests\User\ResetValidator;
use App\User;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $infokpiRepo;

    protected $redirectPath = '/';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function __construct(InformasiRepository $infokpiRepo)
     {
         $this->middleware('guest');
         $this->infokpiRepo    = $infokpiRepo;
     }

    public function showResetForm($token, Request $requests)
    {
        $data = \DB::table('password_resets')->where('token', '=', $token)->first();
        if ($data == null) {
            return redirect('/');
        }
        $email  = $data->email;
        $dataWelcome = $this->infoRepo->showByType('5');
        $welcome     = explode("fileimage:", $dataWelcome['content']);

        return view('auth.passwords.reset', compact('token', 'welcome', 'email'));
    }

    public function reset(ResetValidator $requests)
    {
        $data   = \DB::table('password_resets')->where('token', '=', $requests->token)->first();
        if ($data != null) {
            $user = User::where('email', '=', $requests->email)->update(['password' => bcrypt($requests->password)]);
            $user = User::where('email', '=', $requests->email)->first();
            \DB::table('password_resets')->where('token', '=', $requests->token)->delete();
            \Auth::login($user);
            $prev = \App\PrevilegesPerUsers::where('user_id', \Auth::user()->id)->first()->previlege_id;
            $prevs = \App\PrevilegesPerUsers::with('previlege')->where('user_id', \Auth::user()->id)->get();
            session(['prev' => $prev, 'prevs' => $prevs]);
            return redirect()->intended('/');
        }
        return redirect('/');
    }
}
