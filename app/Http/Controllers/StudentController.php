<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use App\Models\User;
use App\Models\Grade;
use App\Models\Log;
use App\Models\MenuMapping;
use App\Models\RecycleBin;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $datas = Student::dataIndex($request);
            echo json_encode($datas);
        }else{
            $page = "MDST";
            $submoduls = MenuMapping::getMap(session('role_id'),$page);
            return view('dashboard.masterdata.student.index',compact('page','submoduls'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $grades = Grade::all();
        $users = User::getUserListByRole('!=', 4);
        return response()->json(view('dashboard.masterdata.student.form',compact('grades','users'))->render());
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
            'school_name' => 'required',
            'student_grade' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                if(Student::where('user_id', $request->user_id)->count() != 0){
                    return redirect()->back()->with('failed','User has been registered as Student before');
                }else{
                    $data = new Student(array(
                        'user_id' => $request->user_id,
                        'school_name' => $request->school_name,
                        'student_grade' => $request->student_grade,
                    ));

                    $data->save();
                    Log::setLog('MDSTC','Create Student : '.$request->user_id);
                    return redirect()->route('student.index')->with('status','Successfully saved');
                }
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
        $data = Student::where('id', $id)->first();
        $grades = Grade::all();
        $users = User::getUserListByRole('=', 4);
        return response()->json(view('dashboard.masterdata.student.form',compact('data','grades','users'))->render());
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
            'school_name' => 'required',
            'student_grade' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $data = Student::where('id', $id)->update(array(
                    'school_name' => $request->school_name,
                    'student_grade' => $request->student_grade,
                ));
                if($request->type=="profile"){
                    $student = Student::where('id', $id)->first();
                    Log::setLog('MDSTC','Update Student Profile : '.$student->student->name);
                    return redirect()->route('viewProfile')->with('status','Successfully saved');    
                }else{
                    Log::setLog('MDSTC','Update Student : '.$id);
                    return redirect()->route('student.index')->with('status','Successfully saved');    
                }
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
            $data = Student::where('id', $id)->first();
            $log_id = Log::setLog('MDSTD','Delete Student : '.$data->student->name);
            RecycleBin::moveToRecycleBin($log_id, $data->getTable(), json_encode($data));
            $data->delete();
            return "true";
        }catch(\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
