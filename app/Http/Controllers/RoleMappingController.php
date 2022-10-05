<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\RoleMapping;
use App\Role;
use App\Employee;
use App\MenuMapping;
use App\RecycleBin;
use App\Log;

class RoleMappingController extends Controller
{
    public function index(){
        $users = Employee::all();
        $page = MenuMapping::getMap(session('user_id'),"EMRM");
        return view('rolemapping.index',compact('users','page'));
    }

    public function edit($id){
        $user = RoleMapping::where('username',$id)->first();
        $roles = Role::all();
        if($user){
            return view('rolemapping.form',compact('roles','user','id'));
        }else{
            return view('rolemapping.form',compact('roles','id'));
        }
    }

    public function update(Request $request, $id){
        $mapping = RoleMapping::where('username',$id)->first();
        if($mapping){
            $mapping->role_id = $request->role_id;
        }else{
            $mapping = new RoleMapping(array(
                'username' => $id,
                'company_id' => 1,
                'role_id' => $request->role_id,
            ));
        }

        $mapping->save();
        Log::setLog('EMRMU','Update Role:'.$id);
        return redirect()->route('getRoleMapping');
    }

    public function destroy($id){
        $data = RoleMapping::where('username',$id)->first();
        RecycleBin::moveToRecycleBin($data->getTable(), json_encode($data));
        $data->delete();
        Log::setLog('EMRMD','Delete Role:'.$id);

        return redirect()->back();
    }
}
