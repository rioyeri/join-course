<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\Order;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Course;
use App\Models\Package;
use App\Models\Log;
use App\Models\MenuMapping;
use App\Models\RecycleBin;
use App\Models\OrderPayment;
use App\Models\TeacherSchedule;
use App\Models\Day;
use App\Models\OrderDetail;
use App\Helpers\File;
use App\Models\TeacherCourse;
use DateTime;
use PDF;

use PhpOffice\PhpSpreadsheet\IOFactory;

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
        if(session('role_id') == 4){
            $students = Student::where('user_id', session('user_id'))->get();
        }else{
            $students = Student::all();
        }
        $teachers = Teacher::where('status', 1)->get();
        $courses = Course::where('status', 1)->get();
        $grades = Grade::all();
        $packages = Package::where('status', 1)->get();
        $schedules = TeacherSchedule::all();
        $days = Day::all();
        // $users = User::getUserListByRole('!=', 4);
        return response()->json(view('dashboard.order.order.form',compact('students','teachers','courses','grades','packages','schedules','days'))->render());
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
                    "order_type" => $request->optionsRadios,
                    "course_start" => $request->course_start,
                    "order_status" => 0,
                    "payment_status" => 0,
                    "order_token" => $request->_token,
                    "creator" => session('user_id'),
                ));
                $data->save();

                $data->order_id = Order::generateOrderID($data->id);
                $data->save();

                // if(isset($request->teacher_schedules)){
                //     foreach($request->teacher_schedules as $key){
                //         $schedule = TeacherSchedule::where('id', $key)->first();
                //         $date = Day::getStartOfWeekDate($request->course_start, $schedule->day_id);
                //         $schedule_time = date_format(date_create($date->format('Y-m-d')." ".$schedule->time_start), "Y-m-d H:i:s");
    
                //         $detail = new OrderDetail(array(
                //             'order_id' => $data->id,
                //             'schedule_time' => $schedule_time,
                //             'creator' => session('user_id'),
                //         ));
                //         $detail->save();
                //     }
                // }

                foreach($request->schedule_datetime as $key){
                    $schedule_time = date_format(date_create($key), "Y-m-d H:i:s");

                    $detail = new OrderDetail(array(
                        'order_id' => $data->id,
                        'schedule_time' => $schedule_time,
                        'creator' => session('user_id'),
                    ));
                    $detail->save();
                }
 
                if($request->session()->has('user_data') && $request->session()->has('order')){
                    $request->session()->forget('user_data');
                    $request->session()->forget('order');    
                }

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
    public function show(Request $request, $id)
    {
        $data = Order::HistoryOrder($request, $id);
        return $data;
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
        $details = OrderDetail::where('order_id', $id)->get();
        if(session('role_id') == 4){
            $students = Student::where('user_id', session('user_id'))->get();
        }else{
            $students = Student::all();
        }
        $courses = Course::where('status', 1)->get();
        $teacher_course = TeacherCourse::where('course_id', $data->course_id)->select('teacher_id')->get();
        $teachers = Teacher::whereIn('id', $teacher_course)->where('status', 1)->get();
        $grades = Grade::all();
        $packages = Package::where('status', 1)->get();
        // $users = User::getUserListByRole('!=', 4);
        return response()->json(view('dashboard.order.order.form',compact('data','students','teachers','courses','grades','packages','details'))->render());
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
                $data->order_type = $request->optionsRadios;
                $data->course_start = $request->course_start;
                $data->order_token = $request->_token;
                $data->save();

                for($i=0; $i<count($request->schedule_datetime); $i++){
                    $schedule_time = date_format(date_create($request->schedule_datetime[$i]), "Y-m-d H:i:s");

                    if(OrderDetail::where('id', $request->detail_id[$i])->count() != 0){
                        $detail = OrderDetail::where('id', $request->detail_id[$i])->first();
                        $detail->schedule_time = $schedule_time;
                        $detail->creator = session('user_id');
                        $detail->save();
                    }else{
                        $detail = new OrderDetail(array(
                            'order_id' => $data->id,
                            'schedule_time' => $schedule_time,
                            'creator' => session('user_id'),
                        ));
                        $detail->save();
                    }
                }

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
                }elseif($request->status == 1){
                    $text_log = "Approve Order : ".$data->id;
                }elseif($request->status == -1){
                    $text_log = "Decline Order : ".$data->id;
                }else{
                    $text_log = "Cancel Order : ".$data->id;
                }

                $data->order_status = $request->status;
                $data->approve_by = session('user_id');
                $data->save();

                Log::setLog('MDTCS', $text_log);
                return "true";
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    public function neworder(Request $request){
        ini_set('max_execution_time', 30000);

        $data = str_replace(" ","&",$request->user_name);
        $data.= "+".str_replace(" ","&",$request->user_phone);
        $data.= "+".str_replace(" ","&",$request->user_school);
        $data.= "+".str_replace(" ","&",$request->grade_id);
        $order = str_replace(" ","&",$request->course_id);
        $order.= "+".str_replace(" ","&",$request->teacher_id);
        $order.= "+".str_replace(" ","&",$request->package_id);

        if(isset($request->teacher_schedules)){
            for($i=0; $i < count($request->teacher_schedules); $i++){
                if($i == 0){
                    $order.="_".str_replace("&"," ",$request->teacher_schedules[$i]);
                }else{
                    $order.="+".str_replace("&"," ",$request->teacher_schedules[$i]);
                }
            }
        }

        return redirect()->route('get_login_to_order', ['data'=>$data, 'order'=>$order]);
    }

    public function getInvoice(Request $request, $order_id){
        ini_set('max_execution_time', 6000);
        $data = collect();
        $order = Order::where('id', $order_id)->first();
        $order_detail = Order::where('id', $order_id)->get();
        $paid = OrderPayment::where('order_id', $order_id)->where('payment_confirmation', 1)->sum('payment_amount');

        $subtotal = 0;
        $items = collect();
        foreach($order_detail as $detail){
            $item = collect();
            $item->put('package_name', $detail->get_package->name);
            $item->put('course_name', $detail->get_course->name);
            $item->put('grade', $detail->get_grade->name);
            $item->put('teacher_name', $detail->get_teacher->teacher->name);
            $item->put('order_bill', $detail->order_bill);
            $items->push($item);
            $subtotal+=$detail->order_bill;
        }

        $tax_name = "";
        $data->put('invoice_id', $order->order_id);
        $data->put('date', date('d-m-Y'));
        $data->put('student_name', $order->get_student->student->name);
        $data->put('student_phone', $order->get_student->student->phone);
        $data->put('items', $items);
        $data->put('subtotal', $subtotal);
        if($tax_name != ""){
            $tax_value = $subtotal/100*11;
            $data->put('tax_name', $tax_name);
            $data->put('tax_value', $tax_value);
            $subtotal+=$tax_value;    
        }

        if($paid != 0){
            $data->put('paid', $paid);
            $subtotal-=$paid;
        }

        $data->put('total_bill', $subtotal);

        $filename = "Invoice_".$order->order_id;
        $data = json_decode(json_encode($data), FALSE);

        // $pdf = PDF::loadview('dashboard.order.order.invoice',['data'=>$data])->setPaper('a4', 'portrait');
        // $pdf->save(public_path('download/'.$filename.'.pdf'));
        // return $pdf->download($filename.'.pdf');
        // return view('dashboard.order.order.invoice', compact('data'));
        
        if ($request->ajax()) {
            return response()->json(view('dashboard.order.order.invoice',compact('data'))->render());
        }else{
            return view('dashboard.order.order.invoice',compact('data'));
        }
    }

    public function exportOrder(Request $request){
        ini_set('max_execution_time', 3000);

        if($request->type == 0){
            $nametype = "Data_Order_Not_Confirmed";
            $start = $request->notconfirm_start_date;
            $end = $request->notconfirm_end_date;
        }elseif($request->type == 1){
            $nametype = "Data_Order_Confirmed";
            $start = $request->confirm_start_date;
            $end = $request->confirm_end_date;
        }elseif($request->type == 2){
            $nametype = "Data_Order_Finished";
            $start = $request->finish_start_date;
            $end = $request->finish_end_date;
        }elseif($request->type == -1){
            $nametype = "Data_Order_Declined";
            $start = $request->decline_start_date;
            $end = $request->decline_end_date;
        }else{
            $nametype = "Data_Order";
        }

        $date = date('YmdHis');
        $filename = $nametype."_".$date.".xlsx";

        $orders = Order::where('order_status', $request->type);

        if($start != "" && $end != ""){
            $orders->whereBetween(DB::raw('DATE(order.created_at)'), [$start,$end]);
        }elseif($start != "" && $end == ""){
            $orders->whereDate('order.created_at', '>=', $start);
        }elseif($start == "" && $end != ""){
            $orders->whereDate('order.created_at', '<=', $end);
        }

        $orders = $orders->get();

        $array = array();
        $i = 1;

        foreach($orders as $key){
            // ORDER STATUS
            $status = "";
            if($key->order_status == -1){
                $status .= 'Declined';
            }elseif($key->order_status == 1){
                $status .= 'Ongoing';
            }elseif($key->order_status == 2){
                $status .= 'Finish';
            }else{
                $status .= 'Not Yet Confirmed';
            }

            // SCHEDULES
            $schedules = '';
            $order_detail = OrderDetail::where('order_id', $key->id)->get();
            foreach($order_detail as $det){
                $schedules .= date('D, d-m-Y H:i:s', strtotime($det->schedule_time)).", \n";
            }

            // BILL PAID
            $bill_paid = OrderPayment::where('order_id', $key->id)->where('payment_confirmation', 1)->sum('payment_amount');

            // PAYMENT STATUS
            $payment_status = '';
            if($key->payment_status == 1){
                $payment_status .= 'Paid Off';
            }elseif($key->payment_status == 2){
                $payment_status .= 'Overpaid';
            }else{
                $payment_status .= 'Not Yet Paid Off';
            }

            $row = array(
                "#" => $i++,
                "Order ID" => $key->order_id,
                "Student" => $key->get_student->student->name,
                "Grade" => $key->get_grade->name,
                "Course" => $key->get_course->name,
                "Teacher" => $key->get_teacher->teacher->name,
                "Package" => $key->get_package->name,
                "Type" => $key->order_type,
                "Creator" => $key->creator()->first()->name,
                "Status" => $status,
                "Course Start" => $key->course_start,
                "Schedules" => $schedules,
                "Order Bill" => $key->order_bill,
                "Bill Paid" => $bill_paid,
                "Payment Status" => $payment_status,
                "Created At" => $key->created_at->format('Y-m-d H:i:s'),
                "Updated At" => $key->updated_at->format('Y-m-d H:i:s'),
            );
            array_push($array, $row);
        }
        $spreadsheet = File::getNewStandartSpreadSheet($array);

        // new file
        $writer = new Xlsx($spreadsheet);
        $filepath = "download/".$filename;
        $writer->save($filepath);

        // read file
        // $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        // $writer->save($filename);

        File::download($filepath);
    }
}
