<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\TeacherCourse;
use App\Models\TeacherPrice;
use App\Models\TeacherSchedule;
use App\Models\User;
use App\Models\ContentProfile;
use App\Models\ContentHome;
use App\Models\City;
use App\Models\ContentHomeDetail;
use App\Models\ContentPromo;
use App\Models\ContentPromoDetail;
use App\Models\Day;
use App\Models\OrderDetail;
use App\Models\OrderReport;
use App\Models\OrderReview;
use App\Models\Package;
use App\Models\PackageGrade;
use App\Models\PaymentAccount;
use App\Models\RoleMapping;
use App\Models\Student;

class HelperController extends Controller
{
    public function getTeacherFee(Request $request){
        if(isset($request->teacher_id)){
            $fee = TeacherPrice::where('teacher_id', $request->teacher_id)->where('package_id', $request->package_id)->first()->price;
        }else{
            $package = Package::where('id', $request->package_id)->first();
            $fee = $package->price - ($package->price/100*$package->discount_rate);
        }
        return response()->json($fee);
    }

    // use in middleware
    public function getData(Request $request){
        $data = NULL;
        if($request->jenisdata == "get_teacher"){
            if($request->params != NULL){
                $course = Course::where('id', $request->params)->first();
                $list = TeacherCourse::join('teacher as t', 'teacher_course.teacher_id', 't.id')->where('course_id', $request->params)->where('status', 1)->get();
                $append = '<option value="#" disabled selected>Pick your '.$course->name.'\'s Teacher</option>';
        
                foreach($list as $key){
                    $location = "";
                    if($key->teacher->address_city != "" && $key->teacher->address_province != ""){
                        $location .= $key->teacher->address_city.", ".$key->teacher->address_province;
                    }
                    if($location  != ""){
                        $append.='<option value="'.$key->teacher_id.'" data-text="'.$key->isItInstantOrder().'">'.$key->teacher->name.' ('.$location.')</option>';
                    }else{
                        $append.='<option value="'.$key->teacher_id.'" data-text="'.$key->isItInstantOrder().'">'.$key->teacher->name.'</option>';
                    }
                }
        
                $data = array(
                    'append' => $append,
                );
            }
        }elseif($request->jenisdata == "get_package"){
            if($request->params != NULL){
                // $list = TeacherPrice::where('teacher_id', $request->params)->get();
                $list = PackageGrade::join('package as p', 'package_grade.package_id', 'p.id')->where('p.status', 1)->where('grade_id', $request->params)->get();
                $append = '<option value="#" disabled selected>Pick Available Package</option>';
        
                foreach($list as $key){
                    $append.='<option value="'.$key->package_id.'" data-meet="'.$key->number_meet.'" data-price="'.$key->price.'" data-discount="'.$key->discount_rate.'">'.$key->get_package->name.'</option>';
                }
        
                $data = array(
                    'append' => $append,
                );
            }
        }elseif($request->jenisdata == "get_schedule"){
            if($request->params != NULL){
                $list = TeacherSchedule::where('teacher_id', $request->params)->get();
                $append = '';
        
                foreach($list as $key){
                    $time_start = date('H:i', strtotime($key->time_start));
                    $time_end = date('H:i', strtotime($key->time_end));

                    $append.='<option value="'.$key->id.'">'.$key->get_day->nama_hari.', '.$time_start.' - '.$time_end.'</option>';
                }
        
                $data = array(
                    'append' => $append,
                );
            }
        }elseif($request->jenisdata == "generateSchedule"){
            // echo "<pre>";
            // print_r($request->all());
            // die;
            if($request->params != NULL){
                $data = Teacher::getGeneratingTeacherSchedules($request->teacher_id, $request->package_id, $request->params, $request->teacher_schedules);
            }
        }elseif($request->jenisdata == "get_availability"){
            if($request->params != NULL){
                $availability = Teacher::where('id', $request->params)->firstOrFail();
                $append = '';
        
                if($availability->availability == 0){
                    $append .= '<div class="col-sm-2"><div class="radio">';
                    $append .= '<label><input type="radio" name="optionsRadios" id="options-offline" value="offline" checked> Offline</label>';
                    $append .= '</div></div>';
                }elseif($availability->availability == 1){
                    $append .= '<div class="col-sm-2"><div class="radio">';
                    $append .= '<label><input type="radio" name="optionsRadios" id="options-online" value="online" checked> Online</label>';
                    $append .= '</div></div>';
                }else{
                    $append .= '<div class="col-sm-2"><div class="radio">';
                    $append .= '<label><input type="radio" name="optionsRadios" id="options-online" value="online" checked> Online</label>';
                    $append .= '</div></div>';
                    $append .= '<div class="col-sm-2"><div class="radio">';
                    $append .= '<label><input type="radio" name="optionsRadios" id="options-offline" value="offline"> Offline</label>';
                    $append .= '</div></div>';
                }
        
                $data = array(
                    'append' => $append,
                );
            }
        }

        return response()->json($data);
    }

    // use Out of Middleware
    public function getDatas(Request $request){
        $data = NULL;

        if($request->jenisdata == "get_teacher"){
            if($request->params != NULL){
                $course = Course::where('id', $request->params)->first();
                $list = TeacherCourse::join('teacher as t', 'teacher_course.teacher_id', 't.id')->where('t.status', 1)->where('course_id', $request->params)->get();
                $append = '<option value="#" disabled selected>Guru '.$course->name.'</option>';

                foreach($list as $key){
                    $location = "";
                    if($key->teacher->address_city != "" && $key->teacher->address_province != ""){
                        $location .= $key->teacher->address_city.", ".$key->teacher->address_province;
                    }
                    if($location != ""){
                        $append.='<option value="'.$key->teacher_id.'" data-text="'.$key->isItInstantOrder().'" data-availability="'.$key->get_teacher->availability.'">'.$key->teacher->name.' ('.$location.')</option>';
                    }else{
                        $append.='<option value="'.$key->teacher_id.'" data-text="'.$key->isItInstantOrder().'" data-availability="'.$key->get_teacher->availability.'">'.$key->teacher->name.'</option>';
                    }
                }

                $data = array(
                    'append' => $append,
                );
            }
        }elseif($request->jenisdata == "get_package"){
            if($request->params != NULL){
                // $list = TeacherPrice::where('teacher_id', $request->params)->get();
                $list = PackageGrade::join('package as p', 'package_grade.package_id', 'p.id')->where('p.status', 1)->where('grade_id', $request->params)->get();
                $append = '<option value="#" disabled selected>Paket</option>';
        
                foreach($list as $key){
                    $append.='<option value="'.$key->package_id.'" data-price="'.$key->price.'" data-discount="'.$key->discount_rate.'">'.$key->name.'</option>';
                }

                $data = array(
                    'append' => $append,
                );
            }
        }elseif($request->jenisdata == "get_schedule"){
            if($request->params != NULL){
                $list = TeacherSchedule::where('teacher_id', $request->params)->get();
                $append = '';
        
                foreach($list as $key){
                    $time_start = date('H:i', strtotime($key->time_start));
                    $time_end = date('H:i', strtotime($key->time_end));

                    $append.='<option value="'.$key->id.'">'.$key->get_day->nama_hari.', '.$time_start.' - '.$time_end.'</option>';
                }
        
                $data = array(
                    'append' => $append,
                );
            }
        }

        return response()->json($data);
    }

    public function searchTeacherOrSubject(Request $request){
        $word = $request->word;
        // $teacher = Teacher::where('name', 'LIKE', $request->word)->get();
        $courses = Course::where(function ($query) use ($word){
            $query->orWhere('name', 'LIKE', '%'.$word.'%')->orWhere('topic', 'LIKE', '%'.$word.'%');
        })->where('status', 1)->limit(5)->get();

        $result = array();

        if($word != ""){
            foreach($courses as $course){
                array_push($result, $course->name);
            }
        }

        return response()->json($result);
    }

    public function searching(Request $request){
        return redirect()->route('showSearchResult', ['keyword' => $request->searchbox]);
    }

    public function showSearchResult($keyword){
        $courses = Course::where(function ($query) use ($keyword){
            $query->orWhere('name', 'LIKE', '%'.$keyword.'%')->orWhere('topic', 'LIKE', '%'.$keyword.'%');
        })->where('status', 1)->select('id')->get();

        $results = Teacher::getTeacherListByCourse($courses);
        $company_profile = ContentProfile::all();
        return view('landingpage.content.searchresult', compact('company_profile', 'keyword', 'results'));
    }

    public function getAllTeacherData(){
        $result = array();
        $teachers = Teacher::where('status', 1)->get();
        foreach($teachers as $teacher){
            $array = array(
                "value" => $teacher->teacher->name,
                "text" => $teacher->teacher->name,
            );
            array_push($result, $array);
        }

        return response()->json(json_encode($result));
    }

    public function getTeachersDetailbyName(Request $request){
        $user = User::where('name', 'LIKE', '%'.$request->name.'%')->first();
        $teacher = Teacher::where('user_id', $user->id)->first();

        $title = $teacher->title;
        if($teacher->location() != ""){
            $title .= "(".$teacher->location().")";
        }

        $result = array(
            "title" => $title,
            // "title" => $teacher->title,
            "description" => $teacher->description,
            "image" => $user->profilephoto,
        );

        return response()->json($result);
    }

    public function getOrderBill(Request $request){
        $order = Order::where('id', $request->id)->first();
        $payment = OrderPayment::where('order_id', $request->id)->where('payment_confirmation', 1)->sum('payment_amount');
        $order_bill = $order->order_bill - $payment;

        return response()->json($order_bill);
    }

    public function showTeacherDetail($id){
        $teacher = Teacher::getTeacherListByTeacherId($id);
        $id = $id;
        return response()->json(view('landingpage.layout.profile',compact('teacher','id'))->render());
    }

    public function getLocation(Request $request){
        $data = NULL;
        if($request->jenisdata == "getCities"){
            if($request->province != NULL){
                $citys = City::where('provinsi',$request->province)->select('kab_kota')->get();
                $provinsi = City::where('provinsi',$request->province)->first()->provinsi;
                $append = '<option value="#" disabled selected>Cities of '.$provinsi.'</option>';
        
                foreach($citys as $key){
                    if($request->current_city == $key->kab_kota){
                        $append.='<option value="'.$key->kab_kota.'" selected>'.$key->kab_kota.'</option>';
                    }else{
                        $append.='<option value="'.$key->kab_kota.'">'.$key->kab_kota.'</option>';
                    }
                }
        
                $data = array(
                    'append' => $append,
                );
            }
        }elseif($request->jenisdata == "getProvinces"){
            $provinces = City::getProvinsi();
            $append = '<option value="#" disabled selected>Province</option>';

            foreach($provinces as $key){
                if($request->current_province == $key->provinsi){
                    $append .= '<option value="'.$key->provinsi.'" selected>'.$key->provinsi.'</option>';
                }else{
                    $append .= '<option value="'.$key->provinsi.'">'.$key->provinsi.'</option>';
                }
            }

            $data = array(
                'append' => $append,
            );
        }

        return response()->json($data);
    }

    public function showAllTeacher(){
        $results = Teacher::getTeacherList();
        $company_profile = ContentProfile::all();
        $keyword = "";
        return view('landingpage.content.searchresult', compact('company_profile', 'keyword', 'results'));
    }

    public function getAllTeacher(){
        $data = Teacher::getTeacherList();
        // $data = Teacher::all();

        if($data){
            return ApiFormatter::createApi(200, 'Success', $data);
        }else{
            return ApiFormatter::createApi(400, 'Failed');
        }
    }

    public function apigetDatas(Request $request){
        $data = NULL;

        if($request->jenisdata == "get_teacher"){
            if($request->params != NULL){
                $course = Course::where('id', $request->params)->first();
                $list = TeacherCourse::where('course_id', $request->params)->get();
                $append = '<option value="#" disabled selected>Guru '.$course->name.'</option>';

                foreach($list as $key){
                    $location = "";
                    if($key->teacher->address_city != "" && $key->teacher->address_province != ""){
                        $location .= $key->teacher->address_city.", ".$key->teacher->address_province;
                    }
                    if($location != ""){
                        $append.='<option value="'.$key->teacher_id.'" data-text="'.$key->isItInstantOrder().'">'.$key->teacher->name.' ('.$location.')</option>';
                    }else{
                        $append.='<option value="'.$key->teacher_id.'" data-text="'.$key->isItInstantOrder().'">'.$key->teacher->name.'</option>';
                    }
                }

                $data = array(
                    'append' => $append,
                );
            }
        }elseif($request->jenisdata == "get_schedule"){
            if($request->params != NULL){
                $list = TeacherSchedule::where('teacher_id', $request->params)->get();
                $append = '';
        
                foreach($list as $key){
                    $time_start = date('H:i', strtotime($key->time_start));
                    $time_end = date('H:i', strtotime($key->time_end));

                    $append.='<option value="'.$key->id.'">'.$key->get_day->nama_hari.', '.$time_start.' - '.$time_end.'</option>';
                }
        
                $data = array(
                    'append' => $append,
                );
            }
        }

        if($data != NULL){
            return ApiFormatter::createApi(Response::HTTP_OK, 'Success', $data);
        }else{
            return ApiFormatter::createApi(Response::HTTP_BAD_REQUEST, 'Failed');
        }
    }

    public function apiGetReview($id){
        $data = NULL;
        if(OrderReview::where('id', $id)->count() != 0){
            $review = OrderReview::where('id', $id)->first();
            $data = collect($review);
            $photo = User::getPhoto($review->get_order->get_student->student->id);
            $data->put('order_inv', $review->get_order->order_id);
            $data->put('teacher_name', User::shortenName($review->get_teacher->teacher->name));
            $data->put('course_name', $review->get_order->get_course->name);
            $data->put('reviewer_name', $review->get_order->get_student->student->name);
            $data->put('reviewer_photo', $photo);
            $data->put('reviewer_user_id', $review->get_order->get_student->student->id);
        }

        if($data != NULL){
            return ApiFormatter::createApi(Response::HTTP_OK, 'Success', $data);
        }else{
            return ApiFormatter::createApi(Response::HTTP_BAD_REQUEST, 'Failed');
        }
    }

    public function showAllPackage(){
        $results = Package::where('status', 1)->get();
        $company_profile = ContentProfile::all();
        $keyword = "";
        return view('landingpage.content.allpackage', compact('company_profile', 'results'));
    }

    public function checkBeforeDelete(Request $request){
        $id = $request->id;
        $type = $request->type;
        $text = "";

        if($type=="deleteOrder"){
            $payment = OrderPayment::where('order_id',$id)->get();
            $i = 1;

            foreach($payment as $pay){
                $text .= "<b>- ".$pay->invoice_id."</b>";
                if($i % 2 == 0){
                    $text .="<br>";
                }else{
                    $text .="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $i++;
            }
        }elseif($type=="deletePackage"){
            $order = Order::where('package_id', $id)->get();
            $promo = ContentPromo::where('package_id', $id)->get();
            $i = 1;
            $j = 1;

            foreach($order as $ord){
                $text .= "<b>- ".$ord->order_id."</b>";
                if($i % 2 == 0){
                    $text .="<br>";
                }else{
                    $text .="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $i++;
            }

            if($promo->count() != 0){
                $text .= "<br><br>";
            }

            foreach($promo as $pro){
                $text .= "<b>- [Promo] ".$pro->get_package->name."</b><br>";
            }
        }elseif($type=="deleteCourse"){
            $order = Order::where('course_id', $id)->get();
            $teacherCourse = TeacherCourse::where('course_id', $id)->get();
            $i = 1;

            foreach($order as $ord){
                $text .= "<b>- ".$ord->order_id."</b>";
                if($i % 2 == 0){
                    $text .="<br>";
                }else{
                    $text .="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $i++;
            }

            if($teacherCourse->count() != 0){
                $text .= "<br><br>";
            }

            foreach($teacherCourse as $pro){
                $text .= "<b>- [Registered Teacher] ".$pro->teacher->name."</b><br>";
            }
        }elseif($type=="deleteStudent"){
            $order = Order::where('student_id', $id)->get();
            $i = 1;

            foreach($order as $ord){
                $text .= "<b>- ".$ord->order_id."</b>";
                if($i % 2 == 0){
                    $text .="<br>";
                }else{
                    $text .="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $i++;
            }
        }elseif($type=="deleteTeacher"){
            $order = Order::where('teacher_id', $id)->get();
            $teacherCourse = TeacherCourse::where('teacher_id', $id)->get();
            $teacherSchedules = TeacherSchedule::where('teacher_id', $id)->get();
            $teacherReview = OrderReview::where('teacher_id', $id)->get();
            $i = 1;
            $m = 1;

            foreach($order as $ord){
                $text .= "<b>- ".$ord->order_id."</b>";
                if($i % 2 == 0){
                    $text .="<br>";
                }else{
                    $text .="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $i++;
            }

            if($teacherCourse->count() != 0){
                $text .= "<br><br>";
            }

            foreach($teacherCourse as $cou){
                $text .= "<b>- [Registered Course] ".$cou->get_course->name."</b><br>";
            }

            if($teacherSchedules->count() != 0){
                $text .= "<br><br>";
            }

            foreach($teacherSchedules as $sch){
                $text .= "<b>- [Registered Schedule] ".$sch->get_day->day_name.', '.$sch->time_start.' - '.$sch->time_end."</b><br>";
            }

            if($teacherReview->count() != 0){
                $text .= "<br><br>";
            }

            foreach($teacherReview as $rev){
                $text .= "<b>- [Review] ".$rev->get_order->order_id."</b>";
                if($m % 2 == 0){
                    $text .="<br>";
                }else{
                    $text .="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $m++;
            }
        }elseif($type=="deletePaymentAccount"){
            $orderpayment = OrderPayment::where('payment_method', $id)->get();
            $i = 1;

            foreach($orderpayment as $pay){
                $text .= "<b>- ".$pay->invoice_id."</b>";
                if($i % 2 == 0){
                    $text .="<br>";
                }else{
                    $text .="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $i++;
            }
        }elseif($type=="deleteUser"){
            $student = Student::where('user_id', $id)->get();
            $teacher = Teacher::where('user_id', $id)->get();
            $order = Order::where('creator', $id)->get();
            $orderdetail = OrderDetail::where('creator', $id)->get();
            $orderpayment = OrderPayment::where('creator', $id)->get();
            $course = Course::where('creator', $id)->get();
            $package = Package::where('creator', $id)->get();
            $paymentAccount = PaymentAccount::where('creator', $id)->get();
            $contenthome = ContentHome::where('creator', $id)->get();
            $contenthomedetail = ContentHomeDetail::where('creator', $id)->get();
            $contentprofile = ContentProfile::where('creator', $id)->get();
            $contentpromo = ContentPromo::where('creator', $id)->get();
            $contentpromodetail = ContentPromoDetail::where('creator', $id)->get();
            $orderreview = OrderReview::where('creator', $id)->get();
            $orderreport = OrderReport::where('creator', $id)->get();
            $teacherCourse = TeacherCourse::where('creator', $id)->get();
            $teacherSchedules = TeacherSchedule::where('creator', $id)->get();
            $userRole = RoleMapping::where('creator', $id)->get();
            $i = 1;
            $j = 1;

            foreach($student as $stu){
                $text .= "<b>- [as Student] ".$stu->student->name."</b><br>";
            }

            if($teacher->count() != 0){
                $text .= "<br><br>";
            }

            foreach($teacher as $tea){
                $text .= "<b>- [as Student] ".$tea->teacher->name."</b><br>";
            }

            if($order->count() != 0){
                $text .= "<br><br>";
            }

            foreach($order as $ord){
                $text .= "<b>- ".$ord->order_id."</b>";
                if($i % 2 == 0){
                    $text .="<br>";
                }else{
                    $text .="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $i++;
            }

            if($orderdetail->count() != 0){
                $text .= "<br><br>";
            }

            foreach($orderdetail as $det){
                $text .= "<b>- [Schedule] ".$det->get_order->order_id." : ".$det->schedule_time."</b><br>";
            }

            if($orderpayment->count() != 0){
                $text .= "<br><br>";
            }

            foreach($orderpayment as $pay){
                $text .= "<b>- ".$pay->invoice_id."</b>";
                if($j % 2 == 0){
                    $text .="<br>";
                }else{
                    $text .="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $j++;
            }

            if($course->count() != 0){
                $text .= "<br><br>";
            }

            foreach($course as $cou){
                $text .= "<b>- [Course] ".$cou->name."</b><br>";
            }

            if($package->count() != 0){
                $text .= "<br><br>";
            }

            foreach($package as $pac){
                $text .= "<b>- [Package] ".$pac->name."</b><br>";
            }

            if($paymentAccount->count() != 0){
                $text .= "<br><br>";
            }

            foreach($paymentAccount as $acc){
                $text .= "<b>- [Payment Account] ".$acc->account_type." ".$acc->account_number." ".$acc->account_name."</b><br>";
            }

            if($contenthome->count() != 0){
                $text .= "<br><br>";
            }

            foreach($contenthome as $cho){
                $text .= "<b>- [Content] ".$cho->segment."</b><br>";
            }

            if($contenthomedetail->count() != 0){
                $text .= "<br><br>";
            }

            foreach($contenthomedetail as $chd){
                $text .= "<b>- [Detail Content] ".$chd->get_content->segment." : ".$chd->title."</b><br>";
            }

            if($contentprofile->count() != 0){
                $text .= "<br><br>";
            }

            foreach($contentprofile as $cop){
                $text .= "<b>- [Company Profile] ".$cop->title."</b><br>";
            }

            if($contentpromo->count() != 0){
                $text .= "<br><br>";
            }

            foreach($contentpromo as $cpr){
                $text .= "<b>- [Promo] ".$cpr->get_package->name."</b><br>";
            }

            if($contentpromodetail->count() != 0){
                $text .= "<br><br>";
            }

            foreach($contentpromodetail as $cpd){
                $text .= "<b>- [Detail Promo] ".$cpd->get_promo->get_package->name." : ".$cpd->text."</b><br>";
            }

            if($orderreview->count() != 0){
                $text .= "<br><br>";
            }

            foreach($orderreview as $ore){
                $text .= "<b>- [Order's Review] ".$ore->get_order->order_id."</b><br>";
            }

            if($orderreport->count() != 0){
                $text .= "<br><br>";
            }

            foreach($orderreport as $orp){
                $text .= "<b>- [Order's Report] ".$orp->get_order->order_id."</b><br>";
            }

            if($teacherCourse->count() != 0){
                $text .= "<br><br>";
            }

            foreach($teacherCourse as $tec){
                $text .= "<b>- ".$tec->teacher->name."</b><br>";
            }

            if($teacherSchedules->count() != 0){
                $text .= "<br><br>";
            }

            foreach($teacherSchedules as $tcs){
                $text .= "<b>- [Registered Schedule] ".$tcs->get_day->day_name.', '.$tcs->time_start.' - '.$tcs->time_end."</b><br>";
            }

            if($userRole->count() != 0){
                $text .= "<br><br>";
            }

            foreach($userRole as $usr){
                $text .= "<b>- [User Role] ".$usr->username."</b><br>";
            }
        }elseif($type=="deleteRole"){
            $rolesmapping = RoleMapping::where('role_id', $id)->get();
            $i = 1;

            foreach($rolesmapping as $rol){
                $text .= "<b>- ".$rol->username."</b>";
                if($i % 2 == 0){
                    $text .="<br>";
                }else{
                    $text .="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $i++;
            }
        }

        $data = array(
            'title' => "Delete these transaction below first before delete this data",
            'text' => $text,
        );

        return response()->json($data);
    }
}
