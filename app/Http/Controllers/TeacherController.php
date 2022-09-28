<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Grade;
use App\Models\Course;
use App\Models\Log;
use App\Models\RecycleBin;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $datas = Teacher::dataIndex($request);
            echo json_encode($datas);
        }else{
            return view('dashboard.masterdata.teacher.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::all();
        $users = User::getUserListByRole('!=', 4);
        return response()->json(view('dashboard.masterdata.teacher.form',compact('courses','users'))->render());
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
            'user_id' => 'required',
            'teacher_subjects' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                Teacher::setData($request->user_id, $request->teacher_subjects);
                Log::setLog('MDTCC','Create Teacher : '.$request->user_id);
                return redirect()->route('teacher.index')->with('status','Successfully saved');
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = User::select('id', 'name')->where('id', $id)->first();
        $exist_course = array_values(array_column(DB::select("SELECT course_id FROM teacher WHERE user_id LIKE $id"), 'course_id'));
        $courses = Course::all();
        return response()->json(view('dashboard.masterdata.teacher.form',compact('data','courses','exist_course'))->render());
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
            'teacher_subjects' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                Teacher::setData($id, $request->teacher_subjects);
                Log::setLog('MDTCU','Update Teacher : '.$id);
                return redirect()->route('teacher.index')->with('status','Successfully saved');
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
            $datas = Teacher::where('user_id', $id)->get();
            foreach($datas as $data){
                $log_id = Log::setLog('MDTCD','Delete Teacher : '.$data->teacher->name.' ('.$data->get_course->name.')');
                RecycleBin::moveToRecycleBin($log_id, $data->getTable(), json_encode($data));   
                $data->delete(); 
            }
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
                $teacher = Teacher::where('user_id', $id)->get();
                $user = User::where('id', $id)->first();

                if($teacher[0]->status == 0){
                    $new_status = 1;
                    $text_log = "Activate Teacher : ".$user->name;
                }else{
                    $new_status = 0;
                    $text_log = "Deactivate Teacher : ".$user->name;
                }

                foreach($teacher as $t){
                    Teacher::where('id', $t->id)->update(array(
                        'status' => $new_status,
                        'creator' => session('user_id'),    
                    ));
                }

                Log::setLog('MDTCS', $text_log);
                return "true";
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }
}
