<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;

class CheckUser
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
        if(session('isItMaintenance') == "maintenance"){
            // aktifkan jika maintenance, deaktif ketika maintenance
            $request->session()->flush();
            return redirect()->route('maintenance');
        }else{
            // aktifkan jika maintenance
            // $request->session()->put('isItMaintenance', 'maintenance');
            Carbon::setLocale('id');
            if ($request->session()->has('isLoggedIn')) {
                $role = session('role');

                return $next($request);
            }else{
                return redirect()->route('getHome');
            }
        }
    }
}
