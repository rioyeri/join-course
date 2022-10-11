<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\Handler;

use App\Models\Role;
use App\Models\MenuMapping;
use App\Models\Log;
use App\Models\RecycleBin;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $datas = Role::dataIndex($request);
            echo json_encode($datas);
        }else{
            $page = "USRL";
            $submoduls = MenuMapping::getMap(session('role_id'),$page);
            return view('dashboard.user.role.index',compact('page', 'submoduls'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json(view('dashboard.user.role.form')->render());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try {
                $data = new Role(array(
                    // Informasi Pribadi
                    'name' => $request->name,
                    'description' => $request->description,
                    'creator' => session('user_id'),
                ));
                $data->save();
                Log::setLog('USRLC','Create Role : '.$request->name);
                return redirect()->route('role.index')->with('status','Successfully saved');
            } catch (\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Role::where('id',$id)->first();
        return response()->json(view('dashboard.user.role.form',compact('data'))->render());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try {
                $data = Role::where('id',$id)->first();
                $data->name = $request->name;
                $data->description = $request->description;
                $data->creator = session('user_id');
                $data->save();
                Log::setLog('USRLU','Update Role : '.$request->name);
                return redirect()->route('role.index')->with('status','Successfully saved');
            } catch (\Exception $e) {
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $data = Role::where('id',$id)->first();
            $log_id = Log::setLog('USRLD','Delete Role : '.$data->name);
            RecycleBin::moveToRecycleBin($log_id, $data->getTable(), json_encode($data));
            $data->delete();
            return "true";
        // fail
        }catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
