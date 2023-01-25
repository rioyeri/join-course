<?php

namespace App\Http\Controllers;

use App\Models\ContentHomeDetail;
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
use App\Models\RoleMapping;
use App\Models\TeacherSchedule;
use App\Models\Student;

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
            $submoduls = MenuMapping::getMap(session('role_id'),$page);
            return view('dashboard.masterdata.teacher.index',compact('page','submoduls'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::where('status',1)->get();
        $users = User::getUserListByRole('!=', 5);
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
            'availability' => 'required',
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
                    'availability' => $request->availability,
                ));
                $teacher->save();

                $user = User::where('id', $request->user_id)->first();
                RoleMapping::setData($user->username, 5);

                if(Student::where('user_id', $request->user_id)->count() != 0){
                    Student::where('user_id', $request->user_id)->delete();
                }

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
            'availability' => 'required',
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
                $teacher->availability = $request->availability;
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
            if(ContentHomeDetail::where('content_id', 3)->where('link', $id)->count() != 0){
                ContentHomeDetail::where('content_id', 3)->where('link', $id)->delete();
            }
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
        $courses = Course::where('status',1)->get();
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
        $packages = Package::where('status',1)->get();
        
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

    public function updateTeacherProfile(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'teacher_subjects' => 'required',
            'availability' => 'required',
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
                $teacher->availability = $request->availability;
                $teacher->save();

                TeacherCourse::setData($id, $request->teacher_subjects);
                // TeacherPrice::setData($id, $request->package_id, $request->package_price);
                TeacherSchedule::setData($id, $request->day_id, $request->time_start, $request->time_end);

                Log::setLog('MDTCU','Update Teacher Profile : '.$teacher->teacher->name);
                return redirect()->route('viewProfile')->with('status','Successfully saved');
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }
}
