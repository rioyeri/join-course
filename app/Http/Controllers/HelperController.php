<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\TeacherCourse;
use App\Models\TeacherPrice;
use App\Models\User;

class HelperController extends Controller
{
    public function getTeacherFee(Request $request){
        $fee = TeacherPrice::where('teacher_id', $request->teacher_id)->where('package_id', $request->package_id)->first()->price;
        return response()->json($fee);
    }

    public function getData(Request $request){
        if($request->jenisdata == "get_teacher"){
            if($request->params != NULL){
                $course = Course::where('id', $request->params)->first();
                $list = TeacherCourse::where('course_id', $request->params)->get();
                $append = '<option value="#" disabled selected>Pick your '.$course->name.'\'s Teacher</option>';
        
                foreach($list as $key){
                    $append.='<option value="'.$key->teacher_id.'">'.$key->teacher->name.'</option>';
                }
        
                $data = array(
                    'append' => $append,
                );
            }else{
                $data = NULL;
            }
        }elseif($request->jenisdata == "get_package"){
            if($request->params != NULL){
                $list = TeacherPrice::where('teacher_id', $request->params)->get();
                $append = '<option value="#" disabled selected>Pick Available Package</option>';
        
                foreach($list as $key){
                    $append.='<option value="'.$key->package_id.'">'.$key->get_package->name.'</option>';
                }
        
                $data = array(
                    'append' => $append,
                );
            }else{
                $data = NULL;
            }
        }

        return response()->json($data);
    }

    public function searchTeacherOrSubject(Request $request){
        $word = $request->word;
        // $teacher = Teacher::where('name', 'LIKE', $request->word)->get();
        $courses = Course::where(function ($query) use ($word){
            $query->orWhere('name', 'LIKE', $word.'%')->orWhere('topic', 'LIKE', $word.'%');
        })->where('status', 1)->limit(5)->get();

        $result = array();

        if($word != ""){
            foreach($courses as $course){
                array_push($result, $course->name);
            }
        }

        return response()->json($result);
    }

    public function getAllTeacherData(){
        $result = array();
        $teachers = Teacher::all();
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
        $result = array(
            "title" => $teacher->title.' ('.$teacher->location.')',
            "description" => $teacher->description,
            "image" => $user->profilephoto,
        );

        return response()->json($result);
    }

    public function getOrderBill(Request $request){
        $order = Order::where('id', $request->id)->first();

        return response()->json($order->order_bill);
    }
}
