<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use App\Models\ForgotPassword;
use App\Models\Role;
use App\Models\RoleMapping;
use App\Models\User;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Teacher;
use App\Models\TeacherPrice;
use App\Models\Package;
use App\Models\Course;
use App\Models\MenuMapping;
use App\Models\Log;
use App\Models\OrderReview;
use App\Models\Order;
use App\Models\Day;
use App\Models\TeacherSchedule;

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
            'birthdate_year' => 'required',
            'birthdate_month' => 'required',
            'birthdate_date' => 'required',
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

                $birthdate = $request->birthdate_year."-".$request->birthdate_month."-".$request->birthdate_date;

                $user = new User(array(
                    // Informasi Pribadi
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'birthdate' => $birthdate,
                    'regis_date' => now(),
                    // Account
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'bck_pass' => $request->password,
                    'address_province' => $request->address_province,
                    'address_city' => $request->address_city,
            
                ));
                // success
                $user->save();

                // Upload Foto
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

    public function edit(Request $request, $id){
        $data = User::where('id',$id)->first();
        if($request->type == "profile"){
            return response()->json(view('dashboard.user.profile.form', compact('data'))->render());
        }elseif($request->type == "changePassword"){
            return response()->json(view('dashboard.user.profile.changePassword', compact('data'))->render());
        }else{
            return response()->json(view('dashboard.user.user-management.form',compact('data'))->render());
        }
    }

    public function update(Request $request, $id){
        // Validate
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'birthdate_year' => 'required',
            'birthdate_month' => 'required',
            'birthdate_date' => 'required',
        ]);

        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try {
                $birthdate = $request->birthdate_year."-".$request->birthdate_month."-".$request->birthdate_date;

                $user = User::where('id',$id)->first();

                // Informasi Pribadi
                $user->name = $request->name;
                $user->username = $request->username;
                $user->phone = $request->phone;
                $user->email = $request->email;
                $user->birthdate = $birthdate;
                $user->address_province = $request->address_province;
                $user->address_city = $request->address_city;

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

                if(session('user_id') == $id){
                    $request->session()->forget('username');
                    $request->session()->forget('name');
                    $request->session()->forget('photo');

                    $foto = asset(User::getPhoto($user->id));

                    $request->session()->put('username', $user->username);
                    $request->session()->put('name', $user->name);
                    $request->session()->put('photo', $foto);
                    return redirect()->route('viewProfile');
                }

                return redirect()->route('user.index')->with('status', 'Data successfully saved');
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
        $roles = Role::whereIn('id', [4,5])->get();
        $grades = Grade::all();
        $courses = Course::where('status',1)->get();

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
            $user->address_province = $request->address_province;
            $user->address_city = $request->address_city;
            $user->update();

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

            if(session()->has('user_data') && session()->has('order')){
                return redirect()->route('order.index');
            }else{
                return redirect()->route('getHome');
            }
        }catch(\Exception $e){
            $request->session()->flush();
            return redirect()->back()->withErrors($e->getMessage());
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

    public function viewProfile(){
        $page = "";
        $data = User::where('id', session('user_id'))->first();

        // Profile Photo
        $profilephoto = User::getPhoto($data->id);

        if(session('role_id') == 4){
            $grades = Grade::all();
            return  view('dashboard.user.profile.index', compact('page','data','grades','profilephoto'));
        }elseif(session('role_id') == 5){
            $teacher = Teacher::where('user_id', session('user_id'))->first();

            // Subject Course
            $exist_course = array_values(array_column(DB::select("SELECT course_id FROM teacher_course WHERE teacher_id LIKE $teacher->id"), 'course_id'));
            $courses = Course::where('status',1)->get();

            // PRICING
            $exist_packages = TeacherPrice::where('teacher_id', $teacher->id)->get();
            $packages = Package::where('status',1)->get();

            // SCHEDULE
            $days = Day::all();
            $details = TeacherSchedule::where('teacher_id', $teacher->id)->get();

            // INCOME, RATING AND ORDERCOUNT
            $income = Order::getTeacherIncome($teacher->id);
            $rating = OrderReview::getRating($teacher->id);
            $order_count = Order::where('teacher_id', $teacher->id)->where('order_status', 2)->count();

            $stats = json_decode(json_encode(array(
                "income" => $income,
                "order_count" => $order_count,
                "rate" => $rating,
            )),FALSE);
    
            return  view('dashboard.user.profile.index', compact('page','data','courses','exist_course','exist_packages','packages','stats','profilephoto','days','details'));
        }else{
            return  view('dashboard.user.profile.index', compact('page','data','profilephoto'));
        }
    }

    public function changePassword(Request $request, $id){
        // Validate
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
            'password_retype' => 'required|string',
            'old_password' => 'required|string',
        ]);

        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try {
                $user = User::where('id',$id)->first();
                if(Hash::check($request->old_password, $user->password)){
                    if($request->password == $request->old_password){
                        Log::setLog('USUSU','Change Password Failed : '.$user->name);
                        return redirect()->back()->with('failed', 'The new password cannot be the same as the old password');
                    }else{
                        if($request->password != $request->password_retype){
                            Log::setLog('USUSU','Change Password Failed : '.$user->name);
                            return redirect()->back()->with('failed', 'Try Again confirm your new password');
                        }else{
                            // Informasi Pribadi
                            $user->password = Hash::make($request->password);
                            $user->bck_pass = $request->password;
                            $user->update();
                            Log::setLog('USUSU','Change Password : '.$user->name);
                            return redirect()->route('viewProfile')->with('status', 'Password successfully changed');
                        }
                    }
                }else{
                    Log::setLog('USUSU','Change Password Failed : '.$user->name);
                    return redirect()->back()->with('failed', 'The Old password is not match');
                }
            } catch (\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }
}