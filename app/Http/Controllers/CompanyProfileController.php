<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\ContentProfile;
use App\Models\MenuMapping;
use App\Models\Log;

class CompanyProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $datas = ContentProfile::dataIndex($request);
            echo json_encode($datas);
        }else{
            $page = "CTCP";
            $submoduls = MenuMapping::getMap(session('role_id'),$page);
            return view('dashboard.content.company-profile.index', compact('page', 'submoduls'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 
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
        $data = ContentProfile::where('id', $id)->first();
        return response()->json(view('dashboard.content.company-profile.form',compact('data'))->render());
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
        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $data = ContentProfile::where('id', $id)->first();
                $data->content = $request->content;
                $data->creator = session('user_id');
                $data->update();
                Log::setLog('CTCPU','Update Content Profile : '.$data->title);
                return redirect()->route('companyprofile.index')->with('status','Successfully saved');
            }catch(\Exception $e){
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
        // 
    }

    public function changeStatus(Request $request, $id){
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $data = ContentProfile::where('id', $id)->first();
                if($data->status == 0){
                    $new_status = 1;
                    $text_log = "Activate Company Profile : ".$data->title;
                }else{
                    $new_status = 0;
                    $text_log = "Deactivate Company Profile : ".$data->title;
                }
                $data->status = $new_status;
                $data->creator = session('user_id');
                $data->save();
                Log::setLog('CTCPS', $text_log);
                return "true";
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }
}
