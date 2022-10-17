<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\User;
use App\Models\Grade;
use App\Models\Course;
use App\Models\Package;
use App\Models\Log;
use App\Models\MenuMapping;
use App\Models\RecycleBin;

use function PHPSTORM_META\map;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $datas = Order::dataIndex($request);
            echo json_encode($datas);
        }else{
            $page = "OROR";
            $submoduls = MenuMapping::getMap(session('role_id'),$page);
            return view('dashboard.order.order.index',compact('page','submoduls'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $students = Student::all();
        $teachers = Teacher::groupBy('user_id')->get();
        $courses = Course::where('status', 1)->get();
        $grades = Grade::all();
        $packages = Package::where('status', 1)->get();
        // $users = User::getUserListByRole('!=', 4);
        return response()->json(view('dashboard.order.order.form',compact('students','teachers','courses','grades','packages'))->render());
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
            "student_id" => "required|integer",
            "grade_id" => "required|integer",
            "teacher_id" => "required|integer",
            "course_id" => "required|integer",
            "order_bill" => "required|integer",
            "package_id" => "required|integer",
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $data = new Order(array(
                    "student_id" => $request->student_id,
                    "grade_id" => $request->grade_id,
                    "teacher_id" => $request->teacher_id,
                    "course_id" => $request->course_id,
                    "package_id" => $request->package_id,
                    "order_bill" => $request->order_bill,
                    "course_start" => $request->course_start,
                    "order_status" => 0,
                    "payment_status" => 0,
                ));
                $data->save();

                $data->order_id = Order::generateInvoiceID($data->id);
                $data->save();

                Log::setLog('ORORC','Create Order : '.$data->order_id);
                return redirect()->route('order.index')->with('status','Successfully saved');
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
        $data = Order::where('id', $id)->first();
        $students = Student::all();
        $teachers = Teacher::groupBy('user_id')->get();
        $courses = Course::where('status', 1)->get();
        $grades = Grade::all();
        $packages = Package::where('status', 1)->get();
        // $users = User::getUserListByRole('!=', 4);
        return response()->json(view('dashboard.order.order.form',compact('data','students','teachers','courses','grades','packages'))->render());
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
            "student_id" => "required|integer",
            "grade_id" => "required|integer",
            "teacher_id" => "required|integer",
            "course_id" => "required|integer",
            "order_bill" => "required|integer",
            "package_id" => "required|integer",
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $data = Order::where('id', $id)->first();
                $data->student_id = $request->student_id;
                $data->grade_id = $request->grade_id;
                $data->teacher_id = $request->teacher_id;
                $data->course_id = $request->course_id;
                $data->package_id = $request->package_id;
                $data->order_bill = $request->order_bill;
                $data->course_start = $request->course_start;
                $data->save();

                Log::setLog('ORORU','Update Order : '.$data->id);
                return redirect()->route('order.index')->with('status','Successfully saved');
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
            $data = Order::where('id', $id)->first();
            $log_id = Log::setLog('ORORD','Delete Order : '.$data->id);
            RecycleBin::moveToRecycleBin($log_id, $data->getTable(), json_encode($data));
            $data->delete();
            return "true";
        }catch(\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function changeStatus(Request $request, $id){
        // echo "<pre>";
        // print_r($request->all());
        // die;
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'status' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $data = Order::where('id', $id)->first();

                if($request->status == 2){
                    $text_log = "Finishing Order : ".$data->id;
                }if($request->status == 1){
                    $text_log = "Approve Order : ".$data->id;
                }else{
                    $text_log = "Cancel Order : ".$data->id;
                }

                $data->order_status = $request->status;
                $data->save();

                Log::setLog('MDTCS', $text_log);
                return "true";
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }
}
