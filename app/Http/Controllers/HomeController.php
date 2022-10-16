<?php

namespace App\Http\Controllers;

use App\Models\ContentHome;
use App\Models\ContentProfile;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Role;
use App\Models\RoleMapping;
use App\Models\MenuMapping;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;

class HomeController extends Controller
{
    public function index(Request $request){
        if ($request->session()->has('isLoggedIn')) {
            $page = "HOME";
            return view('dashboard.home.index',compact('page'));
        }else{
            $content = ContentHome::getContent();
            $company_profile = ContentProfile::all();
            // echo "<pre>";
            // print_r($content);
            // die;
            return view('landingpage.content.main',compact('content', 'company_profile'));
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
                if(substr($user->profilephoto,0,4) == "http"){
                    $foto = $user->profilephoto;
                }else{
                    $foto = asset(User::getPhoto($user->id));
                }

                $request->session()->put('role', $user->rolemapping()->first()->role()->first()->role_name);
                $request->session()->put('role_id', $user->rolemapping()->first()->role_id);
                $request->session()->put('username', $user->username);
                $request->session()->put('name', $user->name);
                $request->session()->put('user_id', $user->id);
                $request->session()->put('photo', $foto);
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
        $grades = Grade::all();
        $courses = Course::all();

        return view('dashboard.login.register',compact('roles', 'grades','courses'));
    }

    public function post_register(Request $request){
        // Validate
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required',
            'phonenumber' => 'required',
            'username' => 'required',
            'password' => 'required',
            'password_retype' => 'required',
            'optionsRadios' => 'required',
            'birthdate_month' => 'required',
            'birthdate_date' => 'required',
            'birthdate_year' => 'required',
        ]);

        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->with('warning', 'Sign Up Failed');
        }else{
            try{
                $username_count = User::where('username',$request->username)->orWhere('email', $request->email)->count();
                // FOUND
                if($username_count == 0){
                    if($request->password == $request->password_retype){
                        $birthdate = $request->birthdate_year."-".$request->birthdate_month."-".$request->birthdate_date;

                        $user = new User(array(
                            'email' => $request->email,
                            'name' => $request->name,
                            'phone' => $request->phonenumber,
                            'birthdate' => $birthdate,
                            'username' => $request->username,
                            'password' => Hash::make($request->password),
                            'bck_pass' => $request->password,
                            'regis_date' => now(),
                        ));
                        $user->save();
    
                        RoleMapping::setData($user->username,$request->optionsRadios);

                        if($request->optionsRadios == 4){
                            // Student
                            Student::setData($user->id, $request->school_name, $request->student_grade);
                        }elseif($request->optionsRadios == 5){
                            // Teacher
                            Teacher::setData($user->id, $request->teacher_subjects);
                        }
    
                        $request->session()->flush();
    
                        return redirect()->route('getHome')->with('status','Sign up success. Please Sign In');
                    }else{
                        return redirect()->back()->with('warning', 'Sign up failed!');
                    }

                // NOT FOUND
                }else{
                    return redirect()->back()->with('warning', 'Email or Username has been registered, please Sign in');
                }
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }
}
