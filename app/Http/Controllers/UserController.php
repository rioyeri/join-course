<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Mail;

use App\User;
use App\MenuMapping;
use App\Log;
use App\Mail\NewUserMail;
use App\RoleMapping;

class UserController extends Controller
{
    public function index(){
        if(session('role') == "Superadmin" OR session('role') == "Owner" OR session('role') == "Admin"){
            $users = User::all();
        }else{
            $users = User::where('id', session('user_id'))->get();
        }
        $page = MenuMapping::getMap(session('user_id'),"USUS");
        Mail::to("rioyeri@gmail.com")->send(new NewUserMail);
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
                $foto_profil = "noimage.jpg";

                $user = new User(array(
                    // Informasi Pribadi
                    'name' => $request->nama,
                    'address' => $request->alamat,
                    'phone' => $request->telepon,
                    'email' => $request->email,
                    'tmpt_lhr' => $request->tempat_lahir,
                    'tgl_lhr' => $request->tanggal_lahir,
                    'foto_profil' => $foto_profil,
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
                if($request->foto_profil <> NULL|| $request->foto_profil <> ''){
                    $foto_profil = $user->username.'.'.$request->foto_profil->getClientOriginalExtension();
                    $request->foto_profil->move(public_path('assets/images/user/foto/'),$foto_profil);
                }

                $user->foto_profil = $foto_profil;
                $user->save();

                $mapping = new RoleMapping(array(
                    'username' => $user->username,
                    'role_id' => 61,
                ));

                $mapping->save();

                Log::setLog('USUSC','Create User: '.$request->nama);

                Mail::to("rioyeri@gmail.com")->send(new NewUserMail);

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
                if($request->foto_profil <> NULL|| $request->foto_profil <> ''){

                    if (file_exists(public_path('assets/images/user/foto/').$user->foto_profil) && $user->foto_profil != "noimage.jpg") {
                        unlink(public_path('assets/images/user/foto/').$user->foto_profil);
                    }

                    $foto_profil = $user->username.'.'.$request->foto_profil->getClientOriginalExtension();
                    $request->foto_profil->move(public_path('assets/images/user/foto/'),$foto_profil);
                }else{
                    $foto_profil = $user->foto_profil;
                }

                $user->foto_profil = $foto_profil;
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
        if($user->foto_profil != "noimage.jpg"){
            unlink(public_path('assets/images/user/foto/').$user->foto_profil);
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

}
