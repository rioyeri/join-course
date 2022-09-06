<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\Handler;

use App\User;

class AccountController extends Controller
{
    public function getchange_foto(){
        $user = User::where('id',session('user_id'))->first();
        return view('account.change_foto',compact('user'));
    }

    public function getchange_pass(){

        return view('account.change_pass');
    }

    public function change_foto(Request $request){
        // Validate
        $validator = Validator::make($request->all(), [
            'foto_profil' => 'required|file',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $user = User::where('id',session('user_id'))->first();

                if($user){
                    // Upload Foto
                    if($request->foto_profil <> NULL|| $request->foto_profil <> ''){

                        if (file_exists(public_path('assets/images/user/foto/').$user->foto_profil)) {
                            unlink(public_path('assets/images/user/foto/').$user->foto_profil);
                        }

                        $foto_profil = $user->username.'.'.$request->foto_profil->getClientOriginalExtension();
                        $request->foto_profil->move(public_path('assets/images/user/foto/'),$foto_profil);

                        $user->foto_profil = $foto_profil;
                        $user->save();
                    }
                }
                return redirect()->back()->with('status', 'Foto berhasil dirubah');
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    public function change_pass(Request $request){
        // Validate
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $user = User::where('id',session('user_id'))->first();

                $user->password = Hash::make($request->password);
                $user->bck_pass = $request->password;

                $user->save();

                return redirect()->back()->with('status', 'Password berhasil dirubah');
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    public function profile(){
        $user = User::where('id',session('user_id'))->first();

        return view('account.profile',compact('user'));
    }
}
