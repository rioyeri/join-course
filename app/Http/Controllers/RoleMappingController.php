<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\RoleMapping;
use App\Role;
use App\User;
use App\MenuMapping;
use App\SubMapping;

class RoleMappingController extends Controller
{
    public function index(){
        $users = User::all();
        $page = MenuMapping::getMap(session('user_id'),"USRM");
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
        try{
            $mapping = RoleMapping::where('username',$id)->first();
            if($mapping){
                $mapping->role_id = $request->role_id;
            }else{
                $mapping = new RoleMapping(array(
                    'username' => $id,
                    'role_id' => $request->role_id,
                ));
            }

            $mapping->save();

            if($mapping->role_id == 43){
                $array_submodul_id = array("COAU", "COBN", "COGL", "COJD", "COLN", "COPR", "MDDY", "MDSM");
                $mappings = SubMapping::whereIn('submodul_id', $array_submodul_id)->get();
                $user_id = User::where('username', $id)->first()->id;
                foreach($mappings as $submapping){
                    if(MenuMapping::where('submapping_id', $submapping->id)->count() == 0){
                        $store = new MenuMapping(array(
                            'user_id' => $user_id,
                            'submapping_id' => $submapping->id,
                        ));
                        $store->save();
                    }
                }
            }

            return redirect()->route('getRoleMapping');
        }catch(\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function destroy($id){
        $user = RoleMapping::where('username',$id)->first();

        $user->delete();

        return redirect()->back();

    }
}
