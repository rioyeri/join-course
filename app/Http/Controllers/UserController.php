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
    public function index(){
        if(session('role') == "Superadmin" OR session('role') == "Owner" OR session('role') == "Admin"){
            $users = User::all();
        }else{
            $users = User::where('id', session('user_id'))->get();
        }
        $page = MenuMapping::getMap(session('user_id'),"USUS");
        return view('user.index',compact('users','page'));
    }

    public function create(){
        $jenis = "create";
        return view('user.form',compact('jenis'));
    }

    public function store(Request $request){
        // Validate
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required',
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'telepon' => 'required',
            'ktp' => 'required|string',
            'email' => 'required|email|unique:users',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $profilephoto = "noimage.jpg";

                $user = new User(array(
                    // Informasi Pribadi
                    'name' => $request->nama,
                    'address' => $request->alamat,
                    'phone' => $request->telepon,
                    'email' => $request->email,
                    'tmpt_lhr' => $request->tempat_lahir,
                    'tgl_lhr' => $request->tanggal_lahir,
                    'profilephoto' => $profilephoto,
                    // Account
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'bck_pass' => $request->password,
                    'login_status' => 1,
                    // Informasi KTP
                    'ktp' => $request->ktp
                ));
                // success
                $user->save();

                // Upload Foto
                if($request->profilephoto <> NULL|| $request->profilephoto <> ''){
                    $profilephoto = $user->username.'.'.$request->profilephoto->getClientOriginalExtension();
                    $request->profilephoto->move(public_path('assets/images/user/foto/'),$profilephoto);
                }

                $user->profilephoto = $profilephoto;
                $user->save();

                $mapping = new RoleMapping(array(
                    'username' => $user->username,
                    'role_id' => 61,
                ));

                $mapping->save();

                Log::setLog('USUSC','Create User: '.$request->nama);

                return redirect()->route('user.index')->with('status', 'Data berhasil dibuat');
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    public function edit($id){
        $user = User::where('id',$id)->first();
        $jenis = "edit";
        return view('user.form',compact('jenis','user'));
    }

    public function update(Request $request, $id){
        // Validate
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'telepon' => 'required',
            'ktp' => 'required|string',
            'email' => 'required|email',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
        ]);

        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try {
                $user = User::where('id',$id)->first();

                // Informasi Pribadi
                $user->name = $request->nama;
                $user->address = $request->alamat;
                $user->phone = $request->telepon;
                $user->email = $request->email;
                $user->tmpt_lhr = $request->tempat_lahir;
                $user->tgl_lhr = $request->tanggal_lahir;
                // Informasi KTP
                $user->ktp = $request->ktp;

                $user->save();

                // Upload Foto
                if($request->profilephoto <> NULL|| $request->profilephoto <> ''){

                    if (file_exists(public_path('assets/images/user/foto/').$user->profilephoto) && $user->profilephoto != "noimage.jpg") {
                        unlink(public_path('assets/images/user/foto/').$user->profilephoto);
                    }

                    $profilephoto = $user->username.'.'.$request->profilephoto->getClientOriginalExtension();
                    $request->profilephoto->move(public_path('assets/images/user/foto/'),$profilephoto);
                }else{
                    $profilephoto = $user->profilephoto;
                }

                $user->profilephoto = $profilephoto;
                $user->save();

                Log::setLog('USUSU','Update User: '.$request->nama);

                return redirect()->route('user.index')->with('status', 'Data berhasil dirubah');
            } catch (\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    public function destroy($id){
        $user = User::where('id',$id)->first();
        $name = $user->name;
        if($user->profilephoto != "noimage.jpg"){
            unlink(public_path('assets/images/user/foto/').$user->profilephoto);
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
            $request->session()->put('role', $user->rolemapping()->first()->role()->first()->role_name);
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