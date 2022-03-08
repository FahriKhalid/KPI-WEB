<?php

namespace App\Http\Middleware;

use App\Domain\User\Entities\User; 
use Closure;
use Session;
use Auth;
use DB;

class Sso
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
    	  if(isset($_COOKIE['npk'])) { 
    		    $user = User::where('NPK', decodex($_COOKIE['npk']))->orWhere('username', decodex($_COOKIE['npk']))->first();
	          Auth::login($user); 
	          return $next($request);
    	  } 
        
    	  return redirect('http://12.7.2.177:8070/');
    }
}