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
use App\Models\Teacher;
use App\Models\Student;

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
    public function redirect(Request $request){
        if(isset($request->data) && isset($request->order)){
            $request->session()->put('user_data', $request->data);
            $request->session()->put('order', $request->order);    
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request){
        try{
            $user = Socialite::driver('google')->stateless()->user();
        }catch(\Exception $e){
            $request->session()->flush();
            return redirect()->route('getHome')->withErrors($e->getMessage());
        }

        $user_exist = User::where('email',$user->email)->first();

        // NOT FOUND
        if(!$user_exist){
            // GET AVATAR
            $fileContents = file_get_contents($user->avatar);
            $arr = explode("@", $user->email, 2);
            $username = $arr[0];
            $avatar = $username.'.jpg';
            $path = public_path().'/dashboard/assets/users/photos/'.$avatar;
            file_put_contents($path, $fileContents);

            $user_exist = new User(array(
                'name' => $user->name,
                'google_id' => $user->id,
                'profilephoto' => $avatar,
                'email' => $user->email,
                'regis_date' => now(),    
            ));
            $user_exist->save();
        }

        if($user_exist->password == ""){
            return redirect()->route('createPassword', ['id' => $user_exist->id,]);
        }

        $foto = asset(User::getPhoto($user_exist->id));

        User::where('id', $user_exist->id)->update(array(
            "last_login" => now(),
        ));

        $role_id = $user_exist->rolemapping()->first()->role_id;
        $request->session()->put('username', $user_exist->username);
        $request->session()->put('role', $user_exist->rolemapping()->first()->role()->first()->role_name);
        $request->session()->put('role_id', $role_id);
        $request->session()->put('name', $user_exist->name);
        $request->session()->put('user_id', $user_exist->id);
        $request->session()->put('photo', $foto);
        $request->session()->put('isLoggedIn', 'Ya');
        $request->session()->put('isItMaintenance', 'aktif');

        if($role_id == 4){
            $student = Student::where('user_id', $user_exist->id)->first();
            $request->session()->put('student_id', $student->id);
        }elseif($role_id == 5){
            $teacher = Teacher::where('user_id', $user_exist->id)->first();
            $request->session()->put('teacher_id', $teacher->id);
        }

        User::where('id', $user_exist->id)->update(array(
            "last_login" => now(),
        ));

        if($request->session()->has('order') && $request->session()->has('user_data')){
            return redirect()->route('order.index');
        }else{
            return redirect()->route('getHome');
        }
    }
}
