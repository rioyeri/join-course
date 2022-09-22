<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Log;

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
            return view('dashboard.course.course.index');
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
        // return view('dashboard.course.course.form', compact('grades'));
        return response()->json(view('dashboard.course.course.form',compact('grades'))->render());
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
            'grade' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $data = new Course(array(
                    "name" => $request->name,
                    "grade" => $request->grade,
                    "topic" => $request->topic,
                    "description" => $request->description,
                ));
                $data->save();
                // Log::setLog('COCOC','Create Course'.$request->name);
                return redirect()->route('course.index')->with('status','Data berhasil disimpan');
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
        $grades = Grade::all();
        return response()->json(view('dashboard.course.course.form',compact('data','grades'))->render());
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
            'grade' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                Course::where('id', $id)->update(array(
                    "name" => $request->name,
                    "grade" => $request->grade,
                    "topic" => $request->topic,
                    "description" => $request->description,
                ));
                // Log::setLog('COCOC','Create Course'.$request->name);
                return redirect()->route('course.index')->with('status','Data berhasil disimpan');
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
}
