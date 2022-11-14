<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\RoleMapping;
use App\Models\Role;
use App\Models\User;
use App\Models\MenuMapping;
use App\Models\RecycleBin;
use App\Models\Log;
use App\Models\Student;
use App\Models\Teacher;

class RoleMappingController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $datas = RoleMapping::dataIndex($request);
            echo json_encode($datas);
        }else{
            $page = "USRM";
            $submoduls = MenuMapping::getMap(session('role_id'),$page);
            return view('dashboard.user.role-management.index',compact('page', 'submoduls'));
        }
    }

    public function create()
    {
        $usernames = array_values(array_column(DB::select("SELECT username FROM users_role"), 'username'));
        $users = User::whereNotIn('username', $usernames)->get();
        $roles = Role::all();
        return response()->json(view('dashboard.user.role-management.form', compact('users', 'roles'))->render());
    }

    public function store(Request $request)
    {
        // Validate
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'role_id' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try {
                $data = new RoleMapping(array(
                    // Informasi Pribadi
                    'username' => $request->username,
                    'role_id' => $request->role_id,
                    'creator' => session('user_id'),
                ));
                $data->save();
                Log::setLog('USRMC','Create Role Mapping : '.$request->name);
                return redirect()->route('rolemapping.index')->with('status','Successfully saved');
            } catch (\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    public function edit($id){
        $data = RoleMapping::where('id',$id)->first();
        $roles = Role::all();
        return response()->json(view('dashboard.user.role-management.form',compact('data', 'roles'))->render());
    }

    public function update(Request $request, $id)
    {
        // Validate
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try {
                $data = RoleMapping::where('id',$id)->first();
                if($data->role_id == 4){
                    if(Student::where('user_id', $data->user->id)->count() != 0){
                        Student::where('user_id', $data->user->id)->delete();
                    }
                }elseif($data->role_id == 5){
                    if(Teacher::where('user_id', $data->user->id)->count() != 0){
                        Teacher::where('user_id', $data->user->id)->delete();
                    }
                }

                $data->role_id = $request->role_id;
                $data->creator = session('user_id');
                $data->save();

                Log::setLog('USRMU','Update Role Mapping : '.$request->name);
                return redirect()->route('rolemapping.index')->with('status','Successfully saved');
            } catch (\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    public function destroy($id){
        try{
            $data = RoleMapping::where('id',$id)->first();
            if($data->role_id == 4){
                if(Student::where('user_id', $data->user->id)->count() != 0){
                    Student::where('user_id', $data->user->id)->delete();
                }
            }elseif($data->role_id == 5){
                if(Teacher::where('user_id', $data->user->id)->count() != 0){
                    Teacher::where('user_id', $data->user->id)->delete();
                }
            }
            $log_id = Log::setLog('USRMD','Delete Role Mapping : '.$data->username);
            RecycleBin::moveToRecycleBin($log_id, $data->getTable(), json_encode($data));
            $data->delete();
            return "true";
        }catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
