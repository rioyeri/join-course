<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Teacher;
use App\Models\TeacherSchedule;
use App\Models\Log;
use App\Models\MenuMapping;
use App\Models\RecycleBin;
use App\Models\Day;

class TeacherScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $datas = TeacherSchedule::dataIndex($request);
            echo json_encode($datas);
        }else{
            $page = "CTTS";
            $submoduls = MenuMapping::getMap(session('role_id'),$page);
            return view('dashboard.content.teacher-schedule.index',compact('page','submoduls'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $days = Day::all();
        $exist_teacher = TeacherSchedule::select('teacher_id')->get();
        $teachers = Teacher::whereNotIn('id', $exist_teacher)->get();
        return response()->json(view('dashboard.content.teacher-schedule.form',compact('teachers', 'days'))->render());
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
            'teacher_id' => 'required',
            'day_id' => 'required|array',
            'time_start' => 'required|array',
            'time_end' => 'required|array',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $teacher = Teacher::where('id', $request->teacher_id)->first();
                for($i=0; $i < count($request->day_id); $i++){
                    $data = new TeacherSchedule(array(
                        "teacher_id" => $request->teacher_id,
                        "day_id" => $request->day_id[$i],
                        "time_start" => $request->time_start[$i],
                        "time_end" => $request->time_end[$i],
                        "creator" => session('user_id'),
                    ));
                    $data->save();
                }

                Log::setLog('CTTSC','Create Teacher Schedule : '.$teacher->teacher->name);
                return redirect()->route('teacherschedule.index')->with('status','Successfully saved');
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
        $details = TeacherSchedule::where('teacher_id', $id)->get();
        $days = Day::all();
        $exist_teacher = TeacherSchedule::select('teacher_id')->get();
        $teachers = Teacher::whereNotIn('id', $exist_teacher)->get();
        return response()->json(view('dashboard.content.teacher-schedule.form',compact('data','details','days','exist_teacher','teachers'))->render());
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
            'day_id' => 'required|array',
            'time_start' => 'required|array',
            'time_end' => 'required|array',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $teacher = Teacher::where('id', $id)->first();

                if(TeacherSchedule::where('teacher_id', $id)->count() != 0){
                    TeacherSchedule::where('teacher_id', $id)->delete();
                }

                for($i=0; $i < count($request->day_id); $i++){
                    $data = new TeacherSchedule(array(
                        "teacher_id" => $id,
                        "day_id" => $request->day_id[$i],
                        "time_start" => $request->time_start[$i],
                        "time_end" => $request->time_end[$i],
                        "creator" => session('user_id'),
                    ));
                    $data->save();
                }

                Log::setLog('CTTSU','Update Teacher Schedule : '.$teacher->teacher->name);
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
            $teacher = Teacher::where('id', $id)->first();
            $datas = TeacherSchedule::where('teacher_id', $id)->get();
            foreach($datas as $data){
                $log_id = Log::setLog('CTTSD','Delete Teacher Schedule : '.$teacher->teacher->name.' | '.$data->day_id.', '.$data->time_start.'-'.$data->time_end);
                RecycleBin::moveToRecycleBin($log_id, $data->getTable(), json_encode($data));
                $data->delete();
            }

            return "true";
        }catch(\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
