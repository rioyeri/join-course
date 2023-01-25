<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TeacherCourse extends Model
{
    protected $table ='teacher_course';
    protected $fillable = [
        'teacher_id','course_id','status'
    ];

    public function get_course(){
        return $this->belongsTo('App\Models\Course', 'course_id', 'id');
    }

    public function teacher(){
        $teacher = Teacher::where('id', $this->teacher_id)->first()->teacher();
        return $teacher;
    }

    public function get_teacher(){
        return $this->belongsTo('App\Models\Teacher', 'teacher_id', 'id');
    }

    public function isItInstantOrder(){
        $result = false;
        $count_schedule = TeacherSchedule::where('teacher_id', $this->teacher_id)->count();
        if($count_schedule != 0){
            $result = true;
        }

        return $result;
    }

    public static function setData($id, $courses_id){
        if(session()->has('user_id')){
            $user_id = session('user_id');
        }else{
            $user_id = 0;
        }
        $old_list = TeacherCourse::where('teacher_id', $id)->get();
        if($old_list->count() != 0){
            foreach($old_list as $list){
                $status = 0;
                foreach($courses_id as $course){
                    if($list->course_id == $course){
                        $status++;
                    }
                    if(TeacherCourse::where('teacher_id', $id)->where('course_id', $course)->count() == 0){
                        $new = new TeacherCourse(array(
                            "teacher_id" => $id,
                            "course_id" => $course,
                            "creator" => $user_id,
                        ));
                        $new->save();
                    }
                }
                if($status == 0){
                    if(TeacherCourse::where('teacher_id', $id)-> where('course_id', $list->course_id)->count() != 0){
                        $log_id = Log::setLog('MDTCD','Delete Teachers Subject : '.$list->get_course->name);
                        RecycleBin::moveToRecycleBin($log_id, $list->getTable(), json_encode($list));
                        $list->delete();
                    }
                }
            }
        }else{
            foreach($courses_id as $course){
                if(TeacherCourse::where('teacher_id', $id)->where('course_id', $course)->count() == 0){
                    $new = new TeacherCourse(array(
                        "teacher_id" => $id,
                        "course_id" => $course,
                        "creator" => $user_id,
                    ));
                    $new->save();
                }
            }
        }

    }
}
