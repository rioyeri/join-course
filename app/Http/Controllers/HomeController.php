<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class HomeController extends Controller
{
    public function index(Request $request){
        return view('landingpage.content.main');
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

    public function login(Request $request){
        echo "<pre>";
        print_r($request->all());
        die;
        // Validate
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        // IF Validation fail
        if ($validator->fails()) {

            return redirect()->back()->with('warning', 'Username dan password tidak boleh kosong');

        }else{
            $user = User::where('username',$request->username)->first();

            // FOUND
            if($user->name != "" && Hash::check($request->password, $user->password)){
                $request->session()->put('username', $request->username);

                if(empty($user->rolemapping()->first())){
                    $request->session()->put('role',"null");
                }else{
                    $request->session()->put('role', $user->rolemapping()->first()->role()->first()->role_name);
                }
                $request->session()->put('name', $user->name);
                $request->session()->put('user_id', $user->id);
                $request->session()->put('foto', $user->foto_profil);
                $request->session()->put('isLoggedIn', 'Ya');
                $request->session()->put('isItMaintenance', 'aktif');
                // $request->session()->put('isItMaintenance', 'maintenance');
                return redirect()->route('getHome');

            // NOT FOUND
            }else{
                return redirect()->back()->with('warning', 'tidak berhasil login');
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
        return view('login.register');
    }

    public function post_register(Request $request){
        // Validate
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'password' => 'required',
            'passwordcheck' => 'required',
        ]);

        // IF Validation fail
        if ($validator->fails()) {

            return redirect()->back()->with('warning', 'Akun gagal didaftarkan!');

        }else{
            try{
                $username_count = User::where('username',$request->username)->count();
                // FOUND
                if($username_count == 0 && $request->password == $request->passwordcheck){

                    $user = new User(array(
                        'name' => $request->name,
                        'username' => $request->username,
                        'password' => Hash::make($request->password),
                        'bck_pass' => $request->password,
                    ));

                    $user->save();

                    $mapping = new RoleMapping(array(
                        'username' => $user->username,
                        'role_id' => 61,
                    ));

                    $mapping->save();

                    // Menu awal -> view dan update profil sendiri
                    $store = new MenuMapping(array(
                        'user_id' => $user->id,
                        'submapping_id' => "USUSV",
                    ));
                    $store->save();

                    $store = new MenuMapping(array(
                        'user_id' => $user->id,
                        'submapping_id' => "USUSU",
                    ));
                    $store->save();

                    $request->session()->flush();

                    return redirect()->route('getHome2')->with('status','Pendaftaran berhasil. Silahkan login dan melengkapi data anda');
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
