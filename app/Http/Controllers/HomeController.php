<?php

namespace App\Http\Controllers;

use App\Models\ContentHome;
use App\Models\ContentProfile;
use App\Models\ContentPromo;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Role;
use App\Models\RoleMapping;
use App\Models\MenuMapping;
use App\Models\Grade;
use App\Models\Package;
use App\Models\Student;
use App\Models\Teacher;

class HomeController extends Controller
{
    public function index(Request $request){
        // $request->session()->flush();

        // echo "<pre>";
        // print_r(session()->all());
        // die;
        if ($request->session()->has('isLoggedIn')) {
            // $page = "HOME";
            // return view('dashboard.home.index',compact('page'));
            return redirect()->route('home.index');
        }else{
            $content = ContentHome::getContent();
            $company_profile = ContentProfile::all();
            // $promos = ContentPromo::getContent();
            $promos = Package::getContent();
            $grades = Grade::all();
            $packages = Package::where('status', 1)->get();
            $courses = Course::where('status', 1)->get();
            $teachers = Teacher::where('status', 1)->get();

            return view('landingpage.content.main',compact('content', 'company_profile','promos','grades','packages','courses','teachers'));
        }
    }

    public function get_login(Request $request){
        if ($request->session()->has('isLoggedIn')) {
            return redirect()->route('getHome');
        }else{
            return view('dashboard.login.login');
        }
    }

    public function get_login_to_order(Request $request, $data, $order){
        $data = $data;
        $order = $order;
        if ($request->session()->has('isLoggedIn')) {
            return redirect()->route('getHome');
        }else{
            return view('dashboard.login.order-login',compact('data', 'order'));
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
                $foto = asset(User::getPhoto($user->id));

                $role_id = $user->rolemapping()->first()->role_id;
                $request->session()->put('role', $user->rolemapping->role->name);
                $request->session()->put('role_id', $role_id);
                $request->session()->put('username', $user->username);
                $request->session()->put('name', $user->name);
                $request->session()->put('user_id', $user->id);
                $request->session()->put('photo', $foto);
                $request->session()->put('isLoggedIn', 'Ya');
                $request->session()->put('isItMaintenance', 'aktif');

                if($role_id == 4){
                    $student = Student::where('user_id', $user->id)->first();
                    $request->session()->put('student_id', $student->id);
                }elseif($role_id == 5){
                    $teacher = Teacher::where('user_id', $user->id)->first();
                    $request->session()->put('teacher_id', $teacher->id);
                }
                // $request->session()->put('isItMaintenance', 'maintenance');

                User::where('id', $user->id)->update(array(
                    "last_login" => now(),
                ));

                if(isset($request->order)){
                    $request->session()->put('user_data', $request->data);
                    $request->session()->put('order', $request->order);
                    return redirect()->route('order.index');
                }else{
                    return redirect()->route('getHome');
                }
            // NOT FOUND
            }else{
                if(isset($request->order)){
                    return redirect()->route('get_login_to_order')->with('failed', 'tidak berhasil login');
                }else{
                    return redirect()->route('get_login')->with('failed', 'tidak berhasil login');

                }
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
        $roles = Role::whereIn('id', [4,5])->get();
        $grades = Grade::all();
        $courses = Course::where('status',1)->get();

        return view('dashboard.login.register',compact('roles', 'grades','courses'));
    }

    public function get_register_to_order($data, $order){
        $roles = Role::whereIn('id', [4,5])->get();
        $grades = Grade::all();
        $courses = Course::where('status',1)->get();

        return view('dashboard.login.order-register',compact('roles', 'grades','courses','data','order'));
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
            'address_province' => 'required',
            'address_city' => 'required',
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
                            'address_province' => $request->address_province,
                            'address_city' => $request->address_city,
                        ));
                        $user->save();
    
                        RoleMapping::setData($user->username,$request->optionsRadios);

                        if($request->optionsRadios == 4){
                            // Student
                            $student = Student::setData($user->id, $request->school_name, $request->student_grade);
                            $request->session()->put('student_id', $student->id);
                        }elseif($request->optionsRadios == 5){
                            // Teacher
                            $teacher = Teacher::setData($user->id, $request->teacher_subjects);
                            $request->session()->put('teacher_id', $teacher->id);
                        }

                        if(isset($request->order)){
                            $foto = asset(User::getPhoto($user->id));

                            $role_id = $user->rolemapping()->first()->role_id;
                            $request->session()->put('role', $user->rolemapping()->first()->role()->first()->role_name);
                            $request->session()->put('role_id', $role_id);
                            $request->session()->put('username', $user->username);
                            $request->session()->put('name', $user->name);
                            $request->session()->put('user_id', $user->id);
                            $request->session()->put('photo', $foto);
                            $request->session()->put('isLoggedIn', 'Ya');
                            $request->session()->put('user_data', $request->data);
                            $request->session()->put('order', $request->order);
                            return redirect()->route('order.index');
                        }else{
                            $request->session()->flush();
                            return redirect()->route('getHome')->with('status','Sign up success. Please Sign In');
                        }
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
