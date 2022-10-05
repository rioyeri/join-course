<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;
use App\Models\TeacherCourse;
use App\Models\TeacherPrice;
use App\Models\User;
use App\Models\Grade;
use App\Models\Course;
use App\Models\Log;
use App\Models\RecycleBin;
use App\Models\MenuMapping;
use App\Models\Package;

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
            $page = "MDTC";
            return view('dashboard.masterdata.teacher.index',compact('page'));
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
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $teacher = new Teacher(array(
                    'user_id' => $request->user_id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'location' => $request->location,
                ));
                $teacher->save();
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
        $data = Teacher::where('id', $id)->first();
        $page = MenuMapping::getMap(session('role_id'),"MDTC");
        return response()->json(view('dashboard.masterdata.teacher.form',compact('data', 'page'))->render());
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
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $teacher = Teacher::where('id', $id)->first();
                $teacher->title = $request->title;
                $teacher->description = $request->description;
                $teacher->location = $request->location;
                $teacher->save();
                Log::setLog('MDTCU','Update Teacher : '.$teacher->user_id);
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
            $data = Teacher::where('id', $id)->first();
            $log_id = Log::setLog('MDTCD','Delete Teacher : '.$data->teacher->name);
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
                $teacher = Teacher::where('id', $id)->first();

                if($teacher->status == 0){
                    $new_status = 1;
                    $text_log = "Activate Teacher : ".$teacher->teacher->name;
                }else{
                    $new_status = 0;
                    $text_log = "Deactivate Teacher : ".$teacher->teacher->name;
                }

                $teacher->status = $new_status;
                $teacher->save();

                Log::setLog('MDTCS', $text_log);
                return "true";
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    public function editTeacherCourse(Request $request, $id){
        $data = Teacher::where('id', $id)->first();
        $exist_course = array_values(array_column(DB::select("SELECT course_id FROM teacher_course WHERE teacher_id LIKE $id"), 'course_id'));
        $courses = Course::all();
        return response()->json(view('dashboard.masterdata.teacher.course.form',compact('data','courses','exist_course'))->render());
    }

    public function setTeacherCourse(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'teacher_subjects' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                TeacherCourse::setData($id, $request->teacher_subjects);
                Log::setLog('MDTCC','Create Teacher : '.$request->user_id);
                return redirect()->route('teacher.index')->with('status','Successfully saved');
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    public function editTeacherPrice(Request $request, $id){
        $data = Teacher::where('id', $id)->first();
        $exist_packages = TeacherPrice::where('teacher_id', $id)->get();
        $packages = Package::all();
        
        return response()->json(view('dashboard.masterdata.teacher.price.form',compact('data','packages','exist_packages'))->render());
    }

    public function setTeacherPrice(Request $request, $id){
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $teacher = Teacher::where('id', $id)->first();
                TeacherPrice::setData($id, $request->package_id, $request->package_price);
                Log::setLog('MDTCC','Create Teacher : '.$teacher->teacher->name);
                return redirect()->route('teacher.index')->with('status','Successfully saved');
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

}
