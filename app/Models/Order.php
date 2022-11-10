<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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

        $page = MenuMapping::getMap(session('role_id'),"OROR");
        if(session('role_id') == 4 || session('role_id') == 5){
            $order = Order::join('student as s', 'student_id', 's.id')->join('users as us', 's.user_id', 'us.id')->join('teacher as t', 'teacher_id', 't.id')->join('users as ut', 't.user_id', 'ut.id')->join('course as c','course_id','c.id')->join('package as p', 'package_id', 'p.id')->select('order.id','order.order_id','student_id','us.name as student_name','us.phone as student_phone','teacher_id','ut.name as teacher_name','ut.phone as teacher_phone','course_id','c.name as course_name','grade_id','package_id','p.name as package_name','course_start','order_bill','order_status','payment_status','order_type')->where('us.id', session('user_id'));
        }else{
            $order = Order::join('student as s', 'student_id', 's.id')->join('users as us', 's.user_id', 'us.id')->join('teacher as t', 'teacher_id', 't.id')->join('users as ut', 't.user_id', 'ut.id')->join('course as c','course_id','c.id')->join('package as p', 'package_id', 'p.id')->select('order.id','order.order_id','student_id','us.name as student_name','us.phone as student_phone','teacher_id','ut.name as teacher_name','ut.phone as teacher_phone','course_id','c.name as course_name','grade_id','package_id','p.name as package_name','course_start','order_bill','order_status','payment_status','order_type');
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

            $options = '';

            if (array_search("ORORU",$page)){
                if((session('role_id') == 4 || session('role_id') == 5) && ($key->order_status == 1 || $key->order_status == 2)){
                    $options .= '<a class="btn btn-primary btn-round m-5"><i class="fa fa-pencil"></i> Edit</a> ';
                }else{
                    $options .= '<a class="btn btn-primary btn-round m-5" onclick="edit_data('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> Edit</a> ';
                }
            }

            if (array_search("ORORD",$page)){
                $options .= '<a href="javascript:;" class="btn btn-danger btn-round m-5" onclick="delete_data('.$key->id.')"><i class="fa fa-trash-o"></i> Delete</a>';
            }

            $options .= '<input type="hidden" id="route'.$key->id.'" value="'.route('getInvoice',['order_id' => $key->id]).'">';
            $options .= '<a href="javascript:;" class="btn btn-info btn-round m-5" onclick="printPdf('.$key->id.')"><i class="fa fa-file-pdf-o"></i> Invoice</a>';

            $status = '';

            $student_name = $key->student_name;

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
                if($key->order_status == 1 || $key->order_status == 2){
                    $phone_format = User::getFormatWANumber($key->student_phone);
                    $phone_redirect = "https://api.whatsapp.com/send?phone=".$phone_format."&text=Hai%20".$key->student_name.",%20Kami%20dari%20admin%20Flash%20Academia%20memberikan%20informasi%20bahwa%20";
                    $phone_redirect .= Order::getTextInvoice($key->id);
                    $phone = ' <a href="'.$phone_redirect.'" target="_blank"><i class="fa fa-whatsapp"></i></a>';
                    $student_name .= $phone;
                }
            }else{
                if($key->order_status == -1){
                    $status .= '<a class="btn btn-danger m-5"><i class="fa fa-power-off"></i> Declined</a> ';
                }elseif($key->order_status == 1){
                    $status .= '<a class="btn btn-success m-5"><i class="fa fa-power-off"></i> Ongoing</a> ';
                }elseif($key->order_status == 2){
                    $status .= '<a class="btn btn-default m-5"><i class="fa fa-power-off"></i> Finish</a> ';
                }else{
                    $status .= '<a class="btn btn-warning m-5"><i class="fa fa-power-off"></i> Not Confirmed</a>';
                }
            }
            
            $payment_status = '';
            if($key->payment_status == 1){
                $payment_status .= '<a class="btn btn-success m-5"><i class="fa fa-check"></i> Paid Off</a> ';
            }elseif($key->payment_status == 2){
                $payment_status .= '<a class="btn btn-warning m-5"><i class="fa fa-times"></i> Overpaid</a> ';
            }else{
                $payment_status .= '<a class="btn btn-danger m-5"><i class="fa fa-times"></i> Not Yet Paid Off</a>';
            }

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
            $detail->put('order_bill', $key->order_bill);
            $detail->put('bill_paid', $bill_paid);
            $detail->put('payment_status', $payment_status);
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
                $query->orWhere('order.order_id', 'LIKE', '%'.$searchValue.'%')->orWhere('us.name', 'LIKE', '%'.$searchValue.'%')->orWhere('ut.name', 'LIKE', '%'.$searchValue.'%')->orWhere('c.name', 'LIKE', '%'.$searchValue.'%')->orWhere('grade_id', 'LIKE', '%'.$searchValue.'%')->orWhere('p.name', 'LIKE', '%'.$searchValue.'%');
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
            $orderdet = OrderDetail::where('order_id', $key->id)->where('schedule_time', '>=', $today)->orderBy('schedule_time', 'asc')->get();
            if($orderdet->count() == 0){
                $this_schedule = "There is no schedule";
            }else{
                $date = date_create($orderdet[0]->schedule_time);
                $this_schedule = date_format($date, "D, d-m-Y H:i:s");
            }

            $schedule = "";
            if(array_search("DSSCV", $page_schedule)){
                $schedule .= '<a href="" onclick="view_schedule('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-calendar"></i> '.$this_schedule.'</a> ';
            }

            $report = "";
            if(array_search("DSRPV", $page_report)){
                $report .= '<a href="" onclick="view_report('.$key->id.')" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-info"><i class="fa fa-file-text"></i> See Report</a>';
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

        $order = Order::join('student as s', 'student_id', 's.id')->join('users as us', 's.user_id', 'us.id')->join('teacher as t', 'teacher_id', 't.id')->join('users as ut', 't.user_id', 'ut.id')->join('course as c','course_id','c.id')->join('package as p', 'package_id', 'p.id')->select('order.id','order.order_id','student_id','us.name as student_name','teacher_id','ut.name as teacher_name','course_id','c.name as course_name','grade_id','package_id','p.name as package_name','course_start','order_bill','order_status','payment_status','order_type')->whereNotIn('order_status', [-1,1,2]);

        if(session('role_id') == 4){
            $order->where('us.id', session('user_id'));
        }elseif(session('role_id') == 5){
            $order->where('ut.id', session('user_id'));
        }
    
        $totalRecords = $order->count();

        if($searchValue != ''){
            $order->where(function ($query) use ($searchValue) {
                $query->orWhere('order.order_id', 'LIKE', '%'.$searchValue.'%')->orWhere('us.name', 'LIKE', '%'.$searchValue.'%')->orWhere('ut.name', 'LIKE', '%'.$searchValue.'%')->orWhere('c.name', 'LIKE', '%'.$searchValue.'%')->orWhere('grade_id', 'LIKE', '%'.$searchValue.'%')->orWhere('p.name', 'LIKE', '%'.$searchValue.'%');
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
            }else{
                if($key->order_status == -1){
                    $status .= '<a class="btn btn-danger m-5"><i class="fa fa-power-off"></i> Declined</a> ';
                }elseif($key->order_status == 1){
                    $status .= '<a class="btn btn-success m-5"><i class="fa fa-power-off"></i> Ongoing</a> ';
                }elseif($key->order_status == 2){
                    $status .= '<a class="btn btn-default m-5"><i class="fa fa-power-off"></i> Finish</a> ';
                }else{
                    $status .= '<a class="btn btn-warning m-5"><i class="fa fa-power-off"></i> Not Confirmed</a>';
                }
    
            }

            $detail->put('no', $i++);
            $detail->put('order_id', '#'.$key->order_id);
            $detail->put('student_name', $key->student_name);
            $detail->put('grade_id', $key->get_grade->name);
            $detail->put('course_name', $key->course_name);
            $detail->put('teacher_name', $key->teacher_name);
            $detail->put('package_name', $key->package_name);
            $detail->put('order_type', $key->order_type);
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

    public static function getTextInvoice($id){
        // $tes = "https://api.whatsapp.com/send?phone=6285155431131&text=terdapat%20invoice%20order%20yang%20perlu%20anda%20bayarkan%20sebelum%20memulai%20kursus%20yang%20anda%20inginkan.%20Berikut%20detail%20invoicenya%20%3A%0A%0A*Nomor%20Invoice*%20%3A%0A*Jumlah%20yang%20harus%20dibayar*%20%3A%0A*Layanan*%20%3A%0A%0ALakukan%20transfer%20pembayaran%20dan%20jangan%20lupa%20screenshot%20bukti%20transfernya%20untuk%20diupload%20melalui%20link%20berikut%20%3A%0Ahttps%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DbWe62bJKR5k%26t%3D732s%0A%0AJika%20memiliki%20pertanyaan%2C%20silahkan%20hubungi%20kami%20melalui%20nomor%20Whatsapp%20ini.%0A%0ATerimakasih%20%3A)%0AFlash%20Academia";
        $order = Order::where('id', $id)->first();
        $amount = OrderPayment::getRemainingPayment($id);
        $link = route('paymentOrderPage', ['order_id'=>$order->order_id, 'token'=>$order->order_token]);

        $text = "terdapat%20invoice%20order%20yang%20perlu%20anda%20bayarkan%20sebelum%20memulai%20kursus%20yang%20anda%20inginkan.%20Berikut%20detail%20invoicenya%20%3A%0A%0A";
        $text .= "%0A*Nomor%20Invoice*%20%3A%20".$order->order_id;
        $text .= "%0A*Jumlah%20yang%20harus%20dibayar*%20%3A%20Rp%20".number_format($amount,2,",",".");
        $text .= "%0A*Layanan*%20%3A%20".$order->get_package->name;
        $text .= "%0ALakukan%20transfer%20pembayaran%20dan%20jangan%20lupa%20screenshot%20bukti%20transfernya%20untuk%20diupload%20melalui%20link%20berikut%20:%20%0A";
        $text .= "%0A".$link."%0A";
        $text .= "%0AJika%20memiliki%20pertanyaan,%20silahkan%20hubungi%20kami%20melalui%20nomor%20Whatsapp%20ini.";
        $text .= "%0ATerimakasih :)";
        $text .= "%0AFlash Academia";

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
}
