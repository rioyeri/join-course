<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\MenuMapping;
use App\Models\Role;
use App\Models\RecycleBin;
use App\Models\Log;

class MenuController extends Controller
{
    // Menu Mapping
    public function index(Request $request){
        if($request->ajax()){
            $datas = MenuMapping::dataIndex($request);
            echo json_encode($datas);
        }else{
            $page = "USMM";
            $submoduls = MenuMapping::getMap(session('role_id'),$page);
            return view('dashboard.user.menu-management.index',compact('page', 'submoduls'));
        }
    }

    public function edit(Request $request, $id){
        $data = Role::where('id', $id)->first();
        $moduls = json_decode (json_encode(MenuMapping::getAllSubmodul()), FALSE);
        $submappings = array_values(array_column(DB::select("SELECT submapping_id FROM role_submapping WHERE role_id=$id"), 'submapping_id'));
        return response()->json(view('dashboard.user.menu-management.form',compact('data','moduls','submappings'))->render());
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $role = Role::where('id', $id)->first();
                if(isset($request->checkBoxes)){
                    MenuMapping::setData($id, $request->checkBoxes);                    
                }else{
                    MenuMapping::where('role_id', $id)->delete();
                }

                Log::setLog('MDSTC','Update Menu : '.$role->name);
                return redirect()->route('menumapping.index')->with('status','Successfully saved');
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }
}
