<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Role;
use App\Models\RoleMapping;
use App\Models\MenuMapping;

class HomeController extends Controller
{
    public function index(Request $request){
        if ($request->session()->has('isLoggedIn')) {
            return view('dashboard.home.index');
        }else{
            return view('landingpage.content.main');
        }
    }

    public function index2(Request $request){
        if(session('isItMaintenance') == "maintenance"){
            return view('welcome.maintenance');
        }else{
            if ($request->session()->has('isLoggedIn')) {
                return view('home.home');
                // return view('welcome.maintenance');
            }else{
                return view('login.login');
                // return view('welcome.maintenance');
            }
        }
    }

    public function get_login(Request $request){
        if ($request->session()->has('isLoggedIn')) {
            return redirect()->route('getHome');
        }else{
            return view('dashboard.login.login');
        }
    }

    public function login(Request $request){
        // Validate
        $validator = Validator::make($request->all(), [
            'login_id' => 'required',
            'password' => 'required',
        ]);

        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->with('warning', 'Username dan password tidak boleh kosong');
        }else{
            $user = User::where('username',$request->login_id)->orWhere('email', $request->login_id)->first();

            // FOUND
            if(isset($user->name) && Hash::check($request->password, $user->password)){
                if(substr($user->foto_profil,0,4) == "http"){
                    $foto = $user->foto_profil;
                }else{
                    $foto = asset('dashboard/assets/users/photos/'.$user->foto_profil);
                }

                $request->session()->put('role', $user->rolemapping()->first()->role()->first()->role_name);
                $request->session()->put('role_id', $user->rolemapping()->first()->role_id);
                $request->session()->put('username', $user->username);
                $request->session()->put('name', $user->name);
                $request->session()->put('user_id', $user->id);
                $request->session()->put('foto', $foto);
                $request->session()->put('isLoggedIn', 'Ya');
                $request->session()->put('isItMaintenance', 'aktif');
                // $request->session()->put('isItMaintenance', 'maintenance');
                return redirect()->route('getHome');

            // NOT FOUND
            }else{
                return redirect()->route('get_login')->with('failed', 'tidak berhasil login');
            }
        }
    }

    public function maintenance(){
        return view('welcome.maintenance');
    }

    public function logout(Request $request){
        $request->session()->flush();

        return redirect()->route('getHome');
    }

    public function get_register(){
        $roles = Role::whereNotIn('id', [1,2,3])->get();
        return view('dashboard.login.register',compact('roles'));
    }

    public function post_register(Request $request){
        // Validate
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required',
            'username' => 'required',
            'password' => 'required',
            'password_retype' => 'required',
            'optionsRadios' => 'required',
        ]);

        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->with('warning', 'Pendaftaran Gagal');
        }else{
            try{
                $username_count = User::where('username',$request->username)->count();
                // FOUND
                if($username_count == 0 && $request->password == $request->password_retype){

                    $user = new User(array(
                        'email' => $request->email,
                        'name' => $request->name,
                        'username' => $request->username,
                        'password' => Hash::make($request->password),
                        'bck_pass' => $request->password,
                    ));
                    $user->save();

                    $mapping = new RoleMapping(array(
                        'username' => $user->username,
                        'role_id' => $request->optionsRadios,
                    ));
                    $mapping->save();

                    $request->session()->flush();

                    return redirect()->route('getHome')->with('status','Pendaftaran berhasil. Silahkan mencoba login');
                // NOT FOUND
                }else{
                    return redirect()->back()->with('warning', 'Akun gagal didaftarkan!');
                }
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }
}
