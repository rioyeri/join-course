<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
use App\Models\Log;
use App\Models\RecycleBin;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $datas = Course::dataIndex($request);
            echo json_encode($datas);
        }else{
            $page = "MDCO";
            return view('dashboard.masterdata.course.index', compact('page'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json(view('dashboard.masterdata.course.form')->render());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $data = new Course(array(
                    "name" => $request->name,
                    "topic" => $request->topic,
                    "description" => $request->description,
                    "creator" => session('user_id'),
                ));
                $data->save();
                Log::setLog('MDCOC','Create Course : '.$request->name);
                return redirect()->route('course.index')->with('status','Successfully saved');
            }catch(\Exception $e){
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
        $data = Course::where('id', $id)->first();
        return response()->json(view('dashboard.masterdata.course.form',compact('data'))->render());
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
            'name' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                Course::where('id', $id)->update(array(
                    "name" => $request->name,
                    "topic" => $request->topic,
                    "description" => $request->description,
                    "creator" => session('user_id'),
                ));
                Log::setLog('MDCOU','Update Course : '.$request->name);
                return redirect()->route('course.index')->with('status','Successfully saved');
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
        try{
            $data = Course::where('id', $id)->first();
            $log_id = Log::setLog('MDCOD','Delete course : '.$data->name);
            RecycleBin::moveToRecycleBin($log_id, $data->getTable(), json_encode($data));
            $data->delete();
            return "true";
        }catch(\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
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
                $data = Course::where('id', $id)->first();
                if($data->status == 0){
                    $new_status = 1;
                    $text_log = "Activate Course : ".$data->name;
                }else{
                    $new_status = 0;
                    $text_log = "Deactivate Course : ".$data->name;
                }
                $data->status = $new_status;
                $data->creator = session('user_id');
                $data->save();
                Log::setLog('MDCOS', $text_log);
                return "true";
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }
}
