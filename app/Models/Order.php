<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Order extends Model
{
    protected $table ='order';
    protected $fillable = [
        'order_id','student_id','course_id','grade_id','teacher_id','package_id','course_start','order_bill','order_status','payment_status','order_token','order_type','creator','approved_by'
    ];

    public function get_grade(){
        return $this->belongsTo('App\Models\Grade', 'grade_id', 'id');
    }

    public function get_package(){
        return $this->belongsTo('App\Models\Package', 'package_id', 'id');
    }

    public function get_course(){
        return $this->belongsTo('App\Models\Course', 'course_id', 'id');
    }

    public function get_teacher(){
        return $this->belongsTo('App\Models\Teacher', 'teacher_id', 'id');
    }

    public function get_student(){
        return $this->belongsTo('App\Models\Student', 'student_id', 'id');
    }

    public function creator(){
        return $this->belongsTo('App\Models\User','creator', 'id');
    }

    public static function generateOrderID($id){
        $order_id = "FA".date('Ymd')."-".$id;
        return $order_id;
    }

    public static function dataIndex(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $start = $request->start_date;
        $end = $request->end_date;

        $page = MenuMapping::getMap(session('role_id'),"OROR");
        if(session('role_id') == 4 || session('role_id') == 5){
            $order = Order::join('student as s', 'student_id', 's.id')->join('users as us', 's.user_id', 'us.id')->join('teacher as t', 'teacher_id', 't.id')->join('users as ut', 't.user_id', 'ut.id')->join('course as c','course_id','c.id')->join('package as p', 'package_id', 'p.id')->join('order_payment as py', 'order.id', 'py.order_id')->select('order.id','order.order_id','student_id','us.name as student_name','us.phone as student_phone','teacher_id','ut.name as teacher_name','ut.phone as teacher_phone','course_id','c.name as course_name','grade_id','package_id','p.name as package_name','course_start','order_bill','order_status','payment_status','order_type')->where('us.id', session('user_id'));
        }else{
            $order = Order::join('student as s', 'student_id', 's.id')->join('users as us', 's.user_id', 'us.id')->join('teacher as t', 'teacher_id', 't.id')->join('users as ut', 't.user_id', 'ut.id')->join('course as c','course_id','c.id')->join('package as p', 'package_id', 'p.id')->join('order_payment as py', 'order.id', 'py.order_id')->select('order.id','order.order_id','student_id','us.name as student_name','us.phone as student_phone','teacher_id','ut.name as teacher_name','ut.phone as teacher_phone','course_id','c.name as course_name','grade_id','package_id','p.name as package_name','course_start','order_bill','order_status','payment_status','order_type');
        }

        if($start != "" && $end != ""){
            $order->whereBetween(DB::raw('DATE(order.created_at)'), [$start,$end]);
        }elseif($start != "" && $end == ""){
            $order->whereDate('order.created_at', '>=', $start);
        }elseif($start == "" && $end != ""){
            $order->whereDate('order.created_at', '<=', $end);
        }

        if($request->type == "confirm"){
            $order->where('order_status', 1);
        }elseif($request->type == "decline"){
            $order->where('order_status', -1);
        }elseif($request->type == "finish"){
            $order->where('order_status', 2);
        }else{
            $order->where('order_status', 0);
        }

        $totalRecords = $order->count();

        if($searchValue != ''){
            $order->where(function ($query) use ($searchValue) {
                $query->orWhere('order.order_id', 'LIKE', '%'.$searchValue.'%')->orWhere('us.name', 'LIKE', '%'.$searchValue.'%')->orWhere('ut.name', 'LIKE', '%'.$searchValue.'%')->orWhere('c.name', 'LIKE', '%'.$searchValue.'%')->orWhere('grade_id', 'LIKE', '%'.$searchValue.'%')->orWhere('p.name', 'LIKE', '%'.$searchValue.'%')->orWhere('course_start', 'LIKE', '%'.$searchValue.'%')->orWhere('order_bill', 'LIKE', '%'.$searchValue.'%');
            });
        }

        $totalRecordwithFilter = $order->count();

        if($columnName == "no"){
            $order->orderBy('id', $columnSortOrder);
        }else{
            $order->orderBy($columnName, $columnSortOrder);
        }

        $order = $order->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($order as $key){
            $detail = collect();

            $schedules = '';
            $order_detail = OrderDetail::where('order_id', $key->id)->get();
            foreach($order_detail as $det){
                $schedules .= '<li>'.date('D, d-m-Y H:i:s', strtotime($det->schedule_time)).'</li>';
            }
            
            $options = '';
            if(array_search("ORORU",$page)){
                $options .= '<a class="btn btn-primary btn-round m-5 btn-sm" onclick="edit_data('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> Edit</a> ';
            }
            if(array_search("ORORD",$page)){
                $options .= '<a href="javascript:;" class="btn btn-danger btn-round m-5 btn-sm" onclick="delete_data('.$key->id.')"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            if($key->order_status == 1 || $key->order_status == 2){
                $options .= '<a href="" class="btn btn-info btn-round m-5 btn-sm" onclick="view_report('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-file-text"></i> Report</a>';
                if($key->order_status == 2){
                    $options .= '<a onclick="view_review('.$key->id.')" data-toggle="modal" data-target="#myModal" class="btn btn-theme btn-sm btn-round m-5"><i class="fa fa-star" style="color:#FFA500"></i> Review</a>';
                }
            }
            $options .= '<input type="hidden" id="route'.$key->id.'" value="'.route('getInvoice',['order_id' => $key->id]).'">';
            $options .= '<a href="javascript:;" class="btn btn-theme03 btn-round m-5 btn-sm" onclick="printPdf('.$key->id.')"><i class="fa fa-file-pdf-o"></i> Invoice</a>';

            $status = '';
            $student_name = $key->student_name;
            if(session('role_id') != 4 && session('role_id') != 5){
                if($key->order_status == -1){
                    $status .= '<a class="btn btn-danger btn-round m-5 btn-sm" onclick="confirm_order('.$key->id.')"><i class="fa fa-power-off"></i> Declined</a> ';
                }elseif($key->order_status == 1){
                    $status .= '<a class="btn btn-success btn-round m-5 btn-sm" onclick="finishing_order('.$key->id.')"><i class="fa fa-power-off"></i> Ongoing</a> ';
                }elseif($key->order_status == 2){
                    $status .= '<a class="btn btn-default btn-round m-5 btn-sm" onclick="canceling_finish_order('.$key->id.')"><i class="fa fa-power-off"></i> Finish</a> ';
                }else{
                    $status .= '<a class="btn btn-warning btn-round m-5 btn-sm" onclick="confirm_order('.$key->id.')"><i class="fa fa-power-off"></i> Not Confirmed</a>';
                }

                if($key->student_phone != ""){
                    $phone_format = User::getFormatWANumber($key->student_phone);
                    $phone_redirect = "https://api.whatsapp.com/send?phone=".$phone_format."&text=Hai%20".$key->student_name.",%20Kami%20dari%20admin%20Flash%20Academia%20memberikan%20informasi%20bahwa%20";
                    $phone_redirect .= Order::getTextInvoice($key->id);
                    $phone = ' <a href="'.$phone_redirect.'" target="_blank">'.$key->student_name.' <i class="fa fa-whatsapp" style="color: #008374; font-size:15px"></i></a>';
                    $student_name = $phone;
                }else{
                    $copy_text = "Hai ".$key->student_name.", Kami dari admin Flash Academia memberikan informasi bahwa ";
                    $copy_text .= Order::getTextInvoice($key->id, "text");
                    $text = ' <a href="javascript:;" id="copytextbtn'.$key->order_id.'" data-toggle="popover" onclick="copyText(\''.$key->order_id.'\',\''.$copy_text.'\')">'.$key->student_name.' <i class="fa fa-copy" style="color:purple; font-size: 15px;"></i></a>';
                    $student_name = $text;
                }
            }else{
                // if($key->order_status == -1){
                //     $status .= '<a class="btn btn-danger m-5"><i class="fa fa-power-off"></i> Declined</a> ';
                // }elseif($key->order_status == 1){
                //     $status .= '<a class="btn btn-success m-5"><i class="fa fa-power-off"></i> Ongoing</a> ';
                // }elseif($key->order_status == 2){
                //     $status .= '<a class="btn btn-default m-5"><i class="fa fa-power-off"></i> Finish</a> ';
                // }else{
                //     $status .= '<a class="btn btn-warning m-5"><i class="fa fa-power-off"></i> Not Confirmed</a>';
                // }
                $status .= $key->order_status;
            }

            // $payment_status = '';
            // if($key->payment_status == 1){
            //     $payment_status .= '<a class="btn btn-success m-5"><i class="fa fa-check"></i> Paid Off</a> ';
            // }elseif($key->payment_status == 2){
            //     $payment_status .= '<a class="btn btn-warning m-5"><i class="fa fa-times"></i> Overpaid</a> ';
            // }else{
            //     $payment_status .= '<a class="btn btn-danger m-5"><i class="fa fa-times"></i> Not Yet Paid Off</a>';
            // }
            

            $bill_paid = OrderPayment::where('order_id', $key->id)->where('payment_confirmation', 1)->sum('payment_amount');

            $detail->put('no', $i++);
            $detail->put('order_id', '#'.$key->order_id);
            $detail->put('student_name', $student_name);
            $detail->put('grade_id', $key->get_grade->name);
            $detail->put('course_name', $key->course_name);
            $detail->put('teacher_name', $key->teacher_name);
            $detail->put('package_name', $key->package_name);
            $detail->put('order_type', $key->order_type);
            $detail->put('status', $status);
            $detail->put('course_start', $key->course_start);
            $detail->put('schedules', $schedules);
            $detail->put('order_bill', $key->order_bill);
            $detail->put('bill_paid', $bill_paid);
            $detail->put('payment_status', $key->payment_status);
            $detail->put('options', $options);
            $data->push($detail);
        }

        $response = array(
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecordwithFilter,
            'data' => $data,
        );

        return $response;
    }

    public static function getFormatData($user_data, $order){
        $pieces = explode("+", $user_data);
        $name = $pieces[0];
        $phone = $pieces[1];
        $school = $pieces[2];
        $grade = $pieces[3];

        $schedules_id = array();

        if(strpos($order, "_")){
            $course_and_teacher_and_package = substr($order, 0, strpos($order, "_"));
            $pieces2 = explode("+", $course_and_teacher_and_package);
            $course_id = $pieces2[0];
            $teacher_id = $pieces2[1];
            $package_id = $pieces2[2];
            
            $schedules = substr($order, strpos($order, "_") + 1);
            $pieces3 = explode("+", $schedules);
            foreach($pieces3 as $piece){
                array_push($schedules_id, $piece);
            }
        }else{
            $pieces2 = explode("+", $order);
            $course_id = $pieces2[0];
            $teacher_id = $pieces2[1];
            $package_id = $pieces2[2];
        }

        $order_bill = TeacherPrice::where('teacher_id', $teacher_id)->where('package_id', $package_id)->first()->price;

        if(isset($schedules_id) && count($schedules_id) != 0){
            $data = array(
                "name" => $name,
                "student_id" => session('student_id'),
                "phone" => $phone,
                "school" => $school,
                "grade_id" => $grade,
                "course_id" => $course_id,
                "teacher_id" => $teacher_id,
                "package_id" => $package_id,
                "schedules_id" => $schedules_id,
                "order_bill" => $order_bill,
            );    
        }else{
            $data = array(
                "name" => $name,
                "student_id" => session('student_id'),
                "phone" => $phone,
                "school" => $school,
                "grade_id" => $grade,
                "course_id" => $course_id,
                "teacher_id" => $teacher_id,
                "package_id" => $package_id,
                "order_bill" => $order_bill,
            );    
        }
        
        return json_decode(json_encode($data),FALSE);
    }

    public static function dataOngoingDashboard(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $page_schedule = MenuMapping::getMap(session('role_id'),"DSSC");
        $page_report = MenuMapping::getMap(session('role_id'),"DSRP");
        $order = Order::join('student as s', 'student_id', 's.id')->join('users as us', 's.user_id', 'us.id')->join('teacher as t', 'teacher_id', 't.id')->join('users as ut', 't.user_id', 'ut.id')->join('course as c','course_id','c.id')->join('package as p', 'package_id', 'p.id')->select('order.id','order.order_id','student_id','us.name as student_name','teacher_id','ut.name as teacher_name','course_id','c.name as course_name','grade_id','package_id','p.name as package_name','course_start','order_bill','order_status','payment_status','order_type')->where('order_status', 1);

        if(session('role_id') == 4){
            $order->where('us.id', session('user_id'));
        }elseif(session('role_id') == 5){
            $order->where('ut.id', session('user_id'));
        }
    
        $totalRecords = $order->count();

        if($searchValue != ''){
            $order->where(function ($query) use ($searchValue) {
                $query->orWhere('order.order_id', 'LIKE', '%'.$searchValue.'%')->orWhere('us.name', 'LIKE', '%'.$searchValue.'%')->orWhere('ut.name', 'LIKE', '%'.$searchValue.'%')->orWhere('c.name', 'LIKE', '%'.$searchValue.'%')->orWhere('grade_id', 'LIKE', '%'.$searchValue.'%')->orWhere('p.name', 'LIKE', '%'.$searchValue.'%')->orWhere('order_type', 'LIKE', '%'.$searchValue.'%');
            });
        }

        $totalRecordwithFilter = $order->count();

        if($columnName == "no"){
            $order->orderBy('id', $columnSortOrder);
        }else{
            $order->orderBy($columnName, $columnSortOrder);
        }

        $order = $order->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($order as $key){
            $detail = collect();

            $today = date('Y-m-d');
            $this_schedule = "There is no schedule";
            $orderdet = OrderDetail::where('order_id', $key->id)->orderBy('schedule_time', 'asc')->get();
            $next_schedule = OrderDetail::where('order_id', $key->id)->where('schedule_time', '>=', $today)->orderBy('schedule_time', 'asc')->get();
            if($orderdet->count() != 0 && $next_schedule->count() == 0){
                $this_schedule = 'All schedules is finished <i class="fa fa-check" style="color:green"></i>';
            }elseif($next_schedule->count() != 0){
                $date = date_create($next_schedule[0]->schedule_time);
                $this_schedule = date_format($date, "D, d-m-Y H:i:s");
            }

            $schedule = "";
            if(array_search("DSSCV", $page_schedule)){
                $schedule .= '<a href="" onclick="view_schedule('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-calendar"></i> '.$this_schedule.'</a> ';
            }

            $report = "";
            if(array_search("DSRPV", $page_report)){
                $report .= '<a href="" onclick="view_report('.$key->id.')" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-info btn-round btn-sm"><i class="fa fa-file-text"></i> View Report</a>';
            }

            $review = '<a class="btn btn-theme btn-sm btn-round" disabled><i class="fa fa-star-o"></i> Review</a>';

            $now = date("Y-m-d H:i:s", strtotime('+1 hours'));
            $last_schedule = date("Y-m-d H:i:s", strtotime('+2hours'));
            if(OrderDetail::where('order_id', $key->id)->count() != 0){
                $last_schedule = OrderDetail::where('order_id', $key->id)->orderBy('schedule_time','desc')->first()->schedule_time;
            }
            if($last_schedule <= $now){
                $review = '<a onclick="view_review('.$key->id.')" data-toggle="modal" data-target="#myModal" class="btn btn-theme btn-round btn-sm"><i class="fa fa-star" style="color:#FFA500"></i> Review</a>';
            }
            
            $detail->put('no', $i++);
            $detail->put('order_id', '#'.$key->order_id);
            $detail->put('student_name', $key->student_name);
            $detail->put('grade_id', $key->get_grade->name);
            $detail->put('course_name', $key->course_name);
            $detail->put('teacher_name', $key->teacher_name);
            $detail->put('package_name', $key->package_name);
            $detail->put('order_type', $key->order_type);
            $detail->put('schedule', $schedule);
            $detail->put('report', $report);
            $detail->put('review', $review);
            $data->push($detail);
        }

        $response = array(
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecordwithFilter,
            'data' => $data,
        );

        return $response;
    }

    public static function dataNotYetConfirmDashboard(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $order = Order::join('student as s', 'student_id', 's.id')->join('users as us', 's.user_id', 'us.id')->join('teacher as t', 'teacher_id', 't.id')->join('users as ut', 't.user_id', 'ut.id')->join('course as c','course_id','c.id')->join('package as p', 'package_id', 'p.id')->select('order.id','order.order_id','student_id','us.name as student_name','teacher_id','ut.name as teacher_name','course_id','c.name as course_name','grade_id','package_id','p.name as package_name','course_start','order_bill','order_status','payment_status','order_type','us.phone as student_phone')->whereNotIn('order_status', [-1,1,2]);

        if(session('role_id') == 4){
            $order->where('us.id', session('user_id'));
        }elseif(session('role_id') == 5){
            $order->where('ut.id', session('user_id'));
        }
    
        $totalRecords = $order->count();

        if($searchValue != ''){
            $order->where(function ($query) use ($searchValue) {
                $query->orWhere('order.order_id', 'LIKE', '%'.$searchValue.'%')->orWhere('us.name', 'LIKE', '%'.$searchValue.'%')->orWhere('ut.name', 'LIKE', '%'.$searchValue.'%')->orWhere('c.name', 'LIKE', '%'.$searchValue.'%')->orWhere('grade_id', 'LIKE', '%'.$searchValue.'%')->orWhere('p.name', 'LIKE', '%'.$searchValue.'%')->orWhere('order_type', 'LIKE', '%'.$searchValue.'%');
            });
        }

        $totalRecordwithFilter = $order->count();

        if($columnName == "no"){
            $order->orderBy('id', $columnSortOrder);
        }else{
            $order->orderBy($columnName, $columnSortOrder);
        }

        $order = $order->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($order as $key){
            $detail = collect();

            $schedules = '';
            $order_detail = OrderDetail::where('order_id', $key->id)->get();
            foreach($order_detail as $det){
                $schedules .= '<li>'.date('D, d-m-Y H:i:s', strtotime($det->schedule_time)).'</li>';
            }

            $student_name = $key->student_name;

            // $payment_status = '';
            // if($key->payment_status == 1){
            //     $payment_status .= '<a class="btn btn-success m-5"><i class="fa fa-check"></i> Paid Off</a> ';
            // }elseif($key->payment_status == 2){
            //     $payment_status .= '<a class="btn btn-warning m-5"><i class="fa fa-times"></i> Overpaid</a> ';
            // }else{
            //     $payment_status .= '<a class="btn btn-danger m-5"><i class="fa fa-times"></i> Not Yet Paid Off</a>';
            // }

            $status = '';
            if(session('role_id') != 4 && session('role_id') != 5){
                if($key->order_status == -1){
                    $status .= '<a class="btn btn-danger btn-round m-5" onclick="confirm_order('.$key->id.')"><i class="fa fa-power-off"></i> Declined</a> ';
                }elseif($key->order_status == 1){
                    $status .= '<a class="btn btn-success btn-round m-5" onclick="finishing_order('.$key->id.')"><i class="fa fa-power-off"></i> Ongoing</a> ';
                }elseif($key->order_status == 2){
                    $status .= '<a class="btn btn-default btn-round m-5" onclick="canceling_finish_order('.$key->id.')"><i class="fa fa-power-off"></i> Finish</a> ';
                }else{
                    $status .= '<a class="btn btn-warning btn-round m-5" onclick="confirm_order('.$key->id.')"><i class="fa fa-power-off"></i> Not Confirmed</a>';
                }

                if($key->student_phone != ""){
                    $phone_format = User::getFormatWANumber($key->student_phone);
                    $phone_redirect = "https://api.whatsapp.com/send?phone=".$phone_format."&text=Hai%20".$key->student_name.",%20Kami%20dari%20admin%20Flash%20Academia%20memberikan%20informasi%20bahwa%20";
                    $phone_redirect .= Order::getTextInvoice($key->id);
                    $phone = ' <a href="'.$phone_redirect.'" target="_blank"><i class="fa fa-whatsapp"></i></a>';
                    $student_name .= $phone;
                }else{
                    $copy_text = "Hai ".$key->student_name.", Kami dari admin Flash Academia memberikan informasi bahwa ";
                    $copy_text .= Order::getTextInvoice($key->id, "text");
                    $text = ' <a href="javascript:;" id="copytextbtn'.$key->order_id.'" data-toggle="popover" onclick="copyText(\''.$key->order_id.'\',\''.$copy_text.'\')"><i class="fa fa-copy" style="color:purple"></i></a>';
                    $student_name .= $text;
                }
            }else{
                // if($key->order_status == -1){
                //     $status .= '<a class="btn btn-danger m-5"><i class="fa fa-power-off"></i> Declined</a> ';
                // }elseif($key->order_status == 1){
                //     $status .= '<a class="btn btn-success m-5"><i class="fa fa-power-off"></i> Ongoing</a> ';
                // }elseif($key->order_status == 2){
                //     $status .= '<a class="btn btn-default m-5"><i class="fa fa-power-off"></i> Finish</a> ';
                // }else{
                //     $status .= '<a class="btn btn-warning m-5"><i class="fa fa-power-off"></i> Not Confirmed</a>';
                // }
                $status .= $key->order_status;
            }

            $detail->put('no', $i++);
            $detail->put('order_id', '#'.$key->order_id);
            $detail->put('student_name', $student_name);
            $detail->put('grade_id', $key->get_grade->name);
            $detail->put('course_name', $key->course_name);
            $detail->put('teacher_name', $key->teacher_name);
            $detail->put('package_name', $key->package_name);
            $detail->put('schedules', $schedules);
            $detail->put('order_type', $key->order_type);
            $detail->put('payment_status', $key->payment_status);
            $detail->put('order_status', $status);
            $data->push($detail);
        }

        $response = array(
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecordwithFilter,
            'data' => $data,
        );

        return $response;
    }

    public static function getTextInvoice($id, $type=null){
        // $tes = "https://api.whatsapp.com/send?phone=6285155431131&text=terdapat%20invoice%20order%20yang%20perlu%20anda%20bayarkan%20sebelum%20memulai%20kursus%20yang%20anda%20inginkan.%20Berikut%20detail%20invoicenya%20%3A%0A%0A*Nomor%20Invoice*%20%3A%0A*Jumlah%20yang%20harus%20dibayar*%20%3A%0A*Layanan*%20%3A%0A%0ALakukan%20transfer%20pembayaran%20dan%20jangan%20lupa%20screenshot%20bukti%20transfernya%20untuk%20diupload%20melalui%20link%20berikut%20%3A%0Ahttps%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DbWe62bJKR5k%26t%3D732s%0A%0AJika%20memiliki%20pertanyaan%2C%20silahkan%20hubungi%20kami%20melalui%20nomor%20Whatsapp%20ini.%0A%0ATerimakasih%20%3A)%0AFlash%20Academia";
        $order = Order::where('id', $id)->first();
        $amount = OrderPayment::getRemainingPayment($id);
        $link = route('paymentOrderPage', ['order_id'=>$order->order_id, 'token'=>$order->order_token]);

        if($type != null){
            $text = "terdapat invoice order yang perlu anda bayarkan sebelum memulai kursus yang anda inginkan. Berikut detail invoicenya : ";
            $text .= "*Nomor Invoice* : ".$order->order_id.". ";
            $text .= "*Jumlah yang harus dibayar* : Rp ".number_format($amount,2,",",".").". ";
            $text .= "*Layanan* : ".$order->get_package->name.". ";
            $text .= "Lakukan transfer pembayaran dan jangan lupa screenshot bukti transfernya untuk diupload melalui link berikut : ";
            $text .= $link.". ";
            $text .= "Jika memiliki pertanyaan, silahkan hubungi kami melalui nomor Whatsapp ini. ";
            $text .= "Terimakasih :). ";
            $text .= "Flash Academia";
        }else{
            $text = "terdapat%20invoice%20order%20yang%20perlu%20anda%20bayarkan%20sebelum%20memulai%20kursus%20yang%20anda%20inginkan.%20Berikut%20detail%20invoicenya%20%3A%0A%0A";
            $text .= "%0A*Nomor%20Invoice*%20%3A%20".$order->order_id;
            $text .= "%0A*Jumlah%20yang%20harus%20dibayar*%20%3A%20Rp%20".number_format($amount,2,",",".");
            $text .= "%0A*Layanan*%20%3A%20".$order->get_package->name;
            $text .= "%0ALakukan%20transfer%20pembayaran%20dan%20jangan%20lupa%20screenshot%20bukti%20transfernya%20untuk%20diupload%20melalui%20link%20berikut%20:%20%0A";
            $text .= "%0A".$link."%0A";
            $text .= "%0AJika%20memiliki%20pertanyaan,%20silahkan%20hubungi%20kami%20melalui%20nomor%20Whatsapp%20ini.";
            $text .= "%0ATerimakasih :)";
            $text .= "%0AFlash Academia";
        }

        return $text;
    }

    public static function getDataOrderforPayment($order_id, $token){
        $order = Order::where('order_id', $order_id)->where('order_token', $token)->first();
        $paid = 0;

        if(Order::where('order_id', $order_id)->where('order_token', $token)->count() != 0){
            $paid = OrderPayment::where('order_id', $order->id)->where('payment_confirmation', 1)->sum('payment_amount');
        }
        $data = array(
            "id_order" => $order->id,
            "order_id" => $order->order_id,
            "order_bill" => $order->order_bill-$paid,
        );

        return json_decode(json_encode($data),FALSE);
    }

    public static function orderTypeStats($month=null){
        $data = collect();
        $periodic = "";
        if($month != null){
            $start = date('Y-'.$month.'-01'); // hard-coded '01' for first day
            $end  = date('Y-'.$month.'-t');
            $periodic = date('M Y');    
        }
        $colors = Color::getColor()->shuffle();
        $i=0;

        $array_of_type = array("online", "offline");

        foreach($array_of_type as $key){
            $temp = collect();
            if($month != null){
                $count_order = Order::whereIn('order_status', [1,2])->whereDate('created_at', ">=", $start)->whereDate('created_at', "<=", $end)->where('order_type', 'LIKE', $key)->count();
            }else{
                $count_order = Order::whereIn('order_status', [1,2])->where('order_type', 'LIKE', $key)->count();
            }

            $temp->put('order_type', $key);
            $temp->put('order_count', $count_order);
            $temp->put('color', $colors[$i]);
            $temp->put('month_name', $periodic);
            $data->push($temp);
            if($i < 9){
                $i++;
            }else{
                $i=0;
            }
        }

        if(count($data) > 10){
            $qtys = array();
            foreach ($data as $key => $row){
                $qtys[$key] = $row['order_count'];
            }
            array_multisort($qtys, SORT_DESC, $data);
    
            $result = collect();
            for($i=0; $i<10; $i++){
                $result->push($data[$i]);
            }
        }else{
            $result = $data;
        }

        return $result;
    }

    public static function orderReport(){
        $data = collect();
        $year  = date('Y');
        $i=0;

        for($month=1; $month<=12; $month++){
            $temp = collect();
            // $month_name = date('M');
            $month_name = date('Y-m', strtotime($year."-".$month."-01"));
            $count_order = Order::whereIn('order_status', [1,2])->whereMonth('created_at', $month)->whereYear('created_at', $year)->count();

            $temp->put('report_period', $month_name);
            $temp->put('report_count', $count_order);
            $data->push($temp);
            if($i < 9){
                $i++;
            }else{
                $i=0;
            }
        }
        return $data;
    }

    public static function dataIncomingPayment(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $page = MenuMapping::getMap(session('role_id'),"ORPY");
        if(session('role_id') == 4 || session('role_id') == 5){
            $orderpayment = OrderPayment::join('users as u', 'order_payment.creator', 'u.id')->join('payment_account as pa', 'order_payment.payment_method', 'pa.id')->join('order as o', 'order_payment.order_id', 'o.id')->join('student as s', 'o.student_id', 's.id')->join('users as us', 's.user_id', 'us.id')->select('order_payment.id','order_payment.invoice_id','order_payment.order_id as order_fk','o.order_id','o.order_bill','us.name AS student_name','payment_amount','payment_method','pa.account_number','pa.account_type','payment_evidence','order_payment.creator','u.name AS creator_name','payment_confirmation','confirmation_by','order_payment.created_at','order_payment.updated_at')->where('us.id', session('user_id'));
        }else{
            $orderpayment = OrderPayment::join('users as u', 'order_payment.creator', 'u.id')->join('payment_account as pa', 'order_payment.payment_method', 'pa.id')->join('order as o', 'order_payment.order_id', 'o.id')->join('student as s', 'o.student_id', 's.id')->join('users as us', 's.user_id', 'us.id')->select('order_payment.id','order_payment.invoice_id','order_payment.order_id as order_fk','o.order_id','o.order_bill','us.name AS student_name','payment_amount','payment_method','pa.account_number','pa.account_type','payment_evidence','order_payment.creator','u.name AS creator_name','payment_confirmation','confirmation_by','order_payment.created_at','order_payment.updated_at');
        }

        $orderpayment->where('payment_confirmation', 0);

        $totalRecords = $orderpayment->count();

        if($searchValue != ''){
            $orderpayment->where(function ($query) use ($searchValue) {
                $query->orWhere('o.order_id', 'LIKE', '%'.$searchValue.'%')->orWhere('pa.account_type', 'LIKE', '%'.$searchValue.'%')->orWhere('payment_amount', 'LIKE', '%'.$searchValue.'%')->orWhere('order_payment.created_at', 'LIKE', '%'.$searchValue.'%')->orWhere('u.name', 'LIKE', '%'.$searchValue.'%');
            });
        }

        $totalRecordwithFilter = $orderpayment->count();

        if($columnName == "no"){
            $orderpayment->orderBy('id', $columnSortOrder);
        }elseif($columnName == "payment_time"){
            $orderpayment->orderBy('created_at', $columnSortOrder);
        }else{
            $orderpayment->orderBy($columnName, $columnSortOrder);
        }

        $orderpayment = $orderpayment->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($orderpayment as $key){
            $detail = collect();

            $payment_account = PaymentAccount::where('id', $key->payment_method)->first();
            $payment_method = $payment_account->account_type." ".$payment_account->account_number;

            // $student_id = Order::where('id', $key->order_fk)->first()->student_id;
            // $student_name = Student::where('id', $student_id)->first()->student->name;

            if (array_search("ORPYS",$page)){
                if($key->payment_confirmation == 1){
                    $payment_confirmation = '<a class="btn btn-success btn-round m-5" onclick="change_status('.$key->id.',1)"><i class="fa fa-dollar"></i> Payment Confirmed</a> ';
                }elseif($key->payment_confirmation == -1){
                    $payment_confirmation = '<a class="btn btn-danger btn-round m-5" onclick="change_status('.$key->id.',-1)"><i class="fa fa-dollar"></i> Payment Decline</a> ';
                }else{
                    $payment_confirmation = '<a class="btn btn-warning btn-round m-5" onclick="change_status('.$key->id.',0)"><i class="fa fa-dollar"></i> Not Confirmed Yet</a> ';
                }
            }else{
                if($key->payment_confirmation == 1){
                    $payment_confirmation = '<a class="btn btn-success m-5"><i class="fa fa-dollar"></i> Payment Confirmed</a> ';
                }elseif($key->payment_confirmation == -1){
                    $payment_confirmation = '<a class="btn btn-danger m-5"><i class="fa fa-dollar"></i> Payment Decline</a> ';
                }else{
                    $payment_confirmation = '<a class="btn btn-warning m-5"><i class="fa fa-dollar"></i> Not Confirmed Yet</a> ';
                }
            }

            $options = '';
            if (array_search("ORPYU",$page)){
                $options .= '<a class="btn btn-primary btn-round m-5" onclick="edit_data('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> Edit</a> ';
            }
            if (array_search("ORPYD",$page)){
                $options .= '<a href="javascript:;" class="btn btn-danger btn-round m-5" onclick="delete_data('.$key->id.')"><i class="fa fa-trash-o"></i> Delete</a>';
            }

            $detail->put('no', $i++);
            $detail->put('invoice_id', '#'.$key->invoice_id);
            $detail->put('order_id', '#'.$key->order_id);
            // $detail->put('student_name', $student_name);
            $detail->put('student_name', $key->student_name);
            $detail->put('order_bill', $key->order_bill);
            $detail->put('payment_amount', $key->payment_amount);
            $detail->put('payment_method', $payment_method);
            $detail->put('payment_evidence', $key->payment_evidence);
            $detail->put('payment_time', $key->created_at->format('Y-m-d H:i'));
            $detail->put('payment_confirmation', $payment_confirmation);
            $detail->put('creator_name', $key->creator_name);
            $detail->put('options', $options);
            $data->push($detail);
        }

        $response = array(
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecordwithFilter,
            'data' => $data,
        );

        return $response;
    }

    public static function getTeacherIncome($teacher_id){
        $result = 0;
        $orders = Order::where('teacher_id', $teacher_id)->where('order_status', 2)->get();
        foreach($orders as $order){
            $result += OrderPayment::where('order_id', $order->id)->where('payment_confirmation', 1)->sum('payment_amount');
        }

        return $result;
    }

    public static function HistoryOrder($request, $teacher_id){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $orders = Order::join('student as s', 'student_id', 's.id')->join('users as us', 's.user_id', 'us.id')->join('teacher as t', 'teacher_id', 't.id')->join('users as ut', 't.user_id', 'ut.id')->join('course as c','course_id','c.id')->join('package as p', 'package_id', 'p.id')->select('order.id','order.order_id','student_id','us.name as student_name','teacher_id','ut.name as teacher_name','course_id','c.name as course_name','grade_id','package_id','p.name as package_name','course_start','order_type','order_status')->where('teacher_id', $teacher_id)->whereIn('order_status', [1,2]);
        $totalRecords = $orders->count();

        if($searchValue != ''){
            $orders->where(function ($query) use ($searchValue) {
                $detail_id = OrderDetail::select('order_id')->where('schedule_time', 'LIKE', '%'.$searchValue.'%')->get();
                $query->orWhere('us.name', 'LIKE', '%'.$searchValue.'%')->orWhere('ut.name','LIKE', '%'.$searchValue.'%')->orWhere('c.name','LIKE', '%'.$searchValue.'%')->orWhere('grade_id','LIKE', '%'.$searchValue.'%')->orWhere('p.name','LIKE', '%'.$searchValue.'%')->orWhere('order_type','LIKE', '%'.$searchValue.'%')->orWhere('order.order_id', 'LIKE', '%'.$searchValue.'%')->orWhereIn('order.id', $detail_id);
            });
        }

        $totalRecordwithFilter = $orders->count();

        if($columnName == "no"){
            $orders->orderBy('id', $columnSortOrder);
        }else{
            $orders->orderBy($columnName, $columnSortOrder);
        }

        $orders = $orders->offset($row)->limit($rowperpage)->get();

        $datas = collect();
        $i = $row+1;

        foreach ($orders as $order) {
            $schedules = '';
            $order_detail = OrderDetail::where('order_id', $order->id)->get();
            foreach($order_detail as $det){
                $schedules .= '<li>'.date('Y-m-d H:i', strtotime($det->schedule_time)).'</li>';
            }

            $detail = collect();
            $detail->put('no', $i++);
            $detail->put('id',$order->id);
            $detail->put('order_id', $order->order_id);
            $detail->put('student_name', $order->student_name);
            $detail->put('grade_id', $order->grade_id);
            $detail->put('course_name', $order->course_name);
            $detail->put('package_name', $order->package_name);
            $detail->put('order_type', $order->order_type);
            $detail->put('schedules', $schedules);
            $detail->put('status', $order->order_status);
            $datas->push($detail);
        }

        if(count($datas) == 0){
            $message = "Failed";
            $code = Response::HTTP_BAD_REQUEST;
        }else{
            $message = "Success";
            $code = Response::HTTP_OK;
        }

        $response = array(
            'message' => $message,
            'code' => $code,
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecordwithFilter,
            'data' => $datas,
        );

        return json_encode($response);
    }
}
