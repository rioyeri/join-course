<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Teacher;
use App\Models\TeacherCourse;
use App\Models\TeacherPrice;
use Illuminate\Http\Request;

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
}
