<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\RoleMapping;
use App\Models\Grade;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request){
        try{
            $user = Socialite::driver('google')->stateless()->user();
        }catch(\Exception $e){
            return redirect()->route('getHome')->withErrors($e->getMessage());
        }

        $user_exist = User::where('email',$user->email)->first();

        // NOT FOUND
        if(!$user_exist){
            $user_exist = new User(array(
                'name' => $user->name,
                'google_id' => $user->id,
                'profilephoto' => $user->avatar,
                'email' => $user->email,
                'regis_date' => now(),    
            ));
            $user_exist->save();
        }

        if($user_exist->password == ""){
            return redirect()->route('createPassword', ['id' => $user_exist->id,]);
        }

        if(substr($user_exist->profilephoto,0,4) == "http"){
            $foto = $user_exist->profilephoto;
        }else{
            $foto = asset(User::getPhoto($user_exist->id));
        }

        $request->session()->put('username', $user_exist->username);
        $request->session()->put('role', $user_exist->rolemapping()->first()->role()->first()->role_name);
        $request->session()->put('role_id', $user_exist->rolemapping()->first()->role_id);
        $request->session()->put('name', $user_exist->name);
        $request->session()->put('user_id', $user_exist->id);
        $request->session()->put('photo', $foto);
        $request->session()->put('isLoggedIn', 'Ya');
        $request->session()->put('isItMaintenance', 'aktif');
        // $request->session()->put('isItMaintenance', 'maintenance');
        return redirect()->route('getHome');
    }
}
