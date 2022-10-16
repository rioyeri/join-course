<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Exceptions\Handler;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use App\Models\ForgotPassword;
use App\Models\Role;
use App\Models\RoleMapping;
use App\Models\User;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\MenuMapping;
use App\Models\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $datas = User::dataIndex($request);
            echo json_encode($datas);
        }else{
            $page = "USUS";
            $submoduls = MenuMapping::getMap(session('role_id'),$page);
            return view('dashboard.user.user-management.index',compact('page','submoduls'));
        }
    }

    public function create()
    {
        return response()->json(view('dashboard.user.user-management.form')->render());
    }

    public function store(Request $request){
        // Validate
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
            'password_retype' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'birthdate' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }elseif($request->password != $request->password_retype){
            return redirect()->back()->with("failed", "Confirm password failed!");
        }else{
            try{
                $profilephoto = "noimage.jpg";

                $user = new User(array(
                    // Informasi Pribadi
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'birthdate' => $request->birthdate,
                    // 'profilephoto' => $profilephoto,
                    // Account
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'bck_pass' => $request->password,
                ));
                // success
                $user->save();

                // // Upload Foto
                if($request->profilephoto <> NULL|| $request->profilephoto <> ''){
                    $profilephoto = $user->username.'.'.$request->profilephoto->getClientOriginalExtension();
                    $request->profilephoto->move(public_path('dashboard/assets/users/photos/'),$profilephoto);
                }

                $user->profilephoto = $profilephoto;
                $user->save();

                // $mapping = new RoleMapping(array(
                //     'username' => $user->username,
                //     'role_id' => 61,
                // ));
                // $mapping->save();

                Log::setLog('USUSC','Create User: '.$request->nama);

                return redirect()->route('user.index')->with('status', 'Data berhasil dibuat');
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    public function edit($id){
        $data = User::where('id',$id)->first();
        return response()->json(view('dashboard.user.user-management.form',compact('data'))->render());
    }

    public function update(Request $request, $id){
        // Validate
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'birthdate' => 'required',
        ]);

        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try {
                $user = User::where('id',$id)->first();

                // Informasi Pribadi
                $user->name = $request->name;
                $user->username = $request->username;
                $user->phone = $request->phone;
                $user->email = $request->email;
                $user->birthdate = $request->birthdate;

                // Upload Foto
                if($request->profilephoto != NULL || $request->profilephoto != ''){
                    if (file_exists(public_path('dashboard/assets/users/photos/'.$user->profilephoto)) && $user->profilephoto != null) {
                        unlink(public_path('dashboard/assets/users/photos/'.$user->profilephoto));
                    }

                    $profilephoto = $user->username.'.'.$request->profilephoto->getClientOriginalExtension();
                    $request->profilephoto->move(public_path('dashboard/assets/users/photos'), $profilephoto);
                }else{
                    $profilephoto = $user->profilephoto;
                }

                $user->profilephoto = $profilephoto;
                $user->update();

                Log::setLog('USUSU','Update User: '.$request->name);

                return redirect()->route('user.index')->with('status', 'Data berhasil dirubah');
            } catch (\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    public function destroy($id){
        $user = User::where('id',$id)->first();
        $name = $user->name;
        if(file_exists(public_path('dashboard\assets\users\photos/').$user->profilephoto) && $user->profilephoto != null){
            unlink(public_path('dashboard\assets\users\photos/').$user->profilephoto);
        }

        $user->delete();
        Log::setLog('USUSD','Delete User: '.$name);
        return "true";
    }

    public function checkUsernameAvailability(Request $request){
        $count_username = User::where('username', $request->username)->count();
        if($count_username != 0){
            $result = "notavailable";
        }else{
            $result = "available";
        }

        $data = array(
            'text' => $result,
        );

        return response()->json($data);
    }

    public function createPassword($id){
        $user = User::where('id', $id)->first();
        $roles = Role::whereNotIn('id', [1,2,3])->get();
        $grades = Grade::all();
        $courses = Course::all();

        // return view('dashboard.login.register-google', compact('user','roles','grades'));
        return view('dashboard.login.register',compact('roles', 'grades', 'user','courses'));
    }

    public function storePassword($id, Request $request){
        try{
            $birthdate = $request->birthdate_year."-".$request->birthdate_month."-".$request->birthdate_date;

            $user = User::where('id', $id)->first();
            $user->username = $request->username;
            $user->name = $request->name;
            $user->password = Hash::make($request->password);
            $user->bck_pass = $request->password;
            $user->phone = $request->phonenumber;
            $user->birthdate = $birthdate;
            $user->regis_date = now();
            $user->update();

            RoleMapping::setData($user->username,$request->optionsRadios);

            if($request->optionsRadios == 4){
                // Student
                Student::setData($user->id, $request->school_name, $request->student_grade);
            }elseif($request->optionsRadios == 5){
                // Teacher
                Teacher::setData($user->id, $request->teacher_subjects);
            }

            $request->session()->put('username', $user->username);
            $request->session()->put('role', $user->rolemapping()->first()->role()->first()->name);
            $request->session()->put('name', $user->name);
            $request->session()->put('user_id', $user->id);
            $request->session()->put('photo', $user->profilephoto);
            $request->session()->put('isLoggedIn', 'Ya');
            $request->session()->put('isItMaintenance', 'aktif');

            return redirect()->route('getHome');
        }catch(\Exception $e){
            return redirect()->back()->with($e->getMessage());
        }

    }

    public function forgotPassword(Request $request){
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
            $url = "https://";
        }else{
            $url = "http://";
        }
        // Append the host(domain name, ip) to the URL.
        $url.= $_SERVER['HTTP_HOST'];

        if(User::where('email', $request->email)->count() != 0){
            if(User::where('email', $request->email)->first()->password != "" && $request->_token != ""){
                if(ForgotPassword::where('email', $request->email)->count() == 0){
                    $data = new ForgotPassword(array(
                        'email' => $request->email,
                        'token' => $request->_token,
                    ));
                    $data->save();
                }else{
                    ForgotPassword::where('email', $request->email)->update(array(
                        "token" => $request->_token,
                    ));
                }
                Mail::to($request->email)->send(new ResetPasswordMail($url,$request->email));
    
                return redirect()->back()->with('status', 'Check your Email Inbox to reset Password');
            }else{
                return redirect()->back()->with('failed', 'You have not password registered, try to login with google account');
            }
            
        }else{
            return redirect()->back()->with('failed', 'Email never registered');
        }
    }

    public function forgetPassword($email, $token){
        $checktoken = ForgotPassword::where('email',$email)->where('token', $token)->count();
        if($checktoken != 0 && $token != ""){
            return view('dashboard.login.resetpassword',compact('email','token'));
        }else{
            return view('dashboard.login.resetpassword');
        }

    }

    public function resetPassword($email, $token, Request $request){
        // Validate
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'password_retype' => 'required',
        ]);

        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->with('warning', 'Please insert new password and confirm password correctly');
        }else{
            try{
                $timenow = time();
                $checktoken = ForgotPassword::where('email',$email)->where('token', $token)->count();
                $updated_at = strtotime(ForgotPassword::where('email',$email)->where('token', $token)->first()->updated_at);
                if($checktoken != 0 && $token != "" && $updated_at >= ($timenow - 1800)){
                    if($request->password == $request->password_retype){
                        User::where('email', $email)->update(array(
                            'password' => Hash::make($request->password),
                            'bck_pass' => $request->password,
                        ));

                        ForgotPassword::where('email',$email)->delete();
                            
                        $request->session()->flush();

                        return redirect()->route('getHome')->with('status', 'Reset Password Success! Please Login');
                    }else{
                        return redirect()->back()->with('warning', 'Failed to Reset Password');
                    }
                }else{
                    return redirect()->back()->with('failed', 'Your Token is Invalid');
                }
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }
}