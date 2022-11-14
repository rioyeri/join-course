<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

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
use App\Models\Day;

class HelperController extends Controller
{
    public function getTeacherFee(Request $request){
        $fee = TeacherPrice::where('teacher_id', $request->teacher_id)->where('package_id', $request->package_id)->first()->price;
        return response()->json($fee);
    }

    // use in middleware
    public function getData(Request $request){
        $data = NULL;
        if($request->jenisdata == "get_teacher"){
            if($request->params != NULL){
                $course = Course::where('id', $request->params)->first();
                $list = TeacherCourse::where('course_id', $request->params)->get();
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
                $list = TeacherPrice::where('teacher_id', $request->params)->get();
                $append = '<option value="#" disabled selected>Pick Available Package</option>';
        
                foreach($list as $key){
                    $append.='<option value="'.$key->package_id.'" data-meet="'.$key->get_package->number_meet.'">'.$key->get_package->name.'</option>';
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
        }
        // echo "<pre>";
        // print_r($data);
        // die;

        return response()->json($data);
    }

    // use Out of Middleware
    public function getDatas(Request $request){
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
        }elseif($request->jenisdata == "get_package"){
            if($request->params != NULL){
                $list = TeacherPrice::where('teacher_id', $request->params)->get();
                $append = '<option value="#" disabled selected>Paket</option>';
        
                foreach($list as $key){
                    $append.='<option value="'.$key->package_id.'" data-meet="'.$key->get_package->number_meet.'">'.$key->get_package->name.'</option>';
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
}
