<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Teacher extends Model
{
    protected $table ='teacher';
    protected $fillable = [
        'user_id','course_id','status'
    ];

    public function get_course(){
        return $this->belongsTo('App\Models\Course', 'course_id', 'id');
    }

    public function teacher(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public static function dataIndex(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $page = MenuMapping::getMap(session('role_id'),"MDTC");
        $teacher = Teacher::join('users as u', 'teacher.user_id', 'u.id')->select('teacher.id','u.name as teacher_name', 'teacher.user_id');


        $teacher->groupBy('u.id');

        $totalRecords = $teacher->count();
        // $totalRecords = 0;
        // foreach($sales->get() as $count){
        //     $totalRecords++;
        // }

        if($searchValue != ''){
            $teacher->where(function ($query) use ($searchValue) {
                $course_ids = Course::select('id')->where('name', 'LIKE', '%'.$searchValue.'%')->get();
                $query->orWhere('teacher_name', 'LIKE', '%'.$searchValue.'%')->orWhereIn('course_id', $course_ids);
            });
        }

        $totalRecordwithFilter = $teacher->count();
        // $totalRecordwithFilter = 0;
        // foreach($sales->get() as $count){
        //     $totalRecordwithFilter++;
        // }

        if($columnName == "no"){
            $teacher->orderBy('id', $columnSortOrder);
        }else{
            $teacher->orderBy($columnName, $columnSortOrder);
        }

        $teacher = $teacher->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($teacher as $key){
            $detail = collect();
            $subjects = '';

            foreach(Teacher::where('user_id', $key->user_id)->get() as $key){
                $subjects .= '<li>'.$key->get_course->name.'</li>';
            }

            $options = '';

            if (array_search("MDTCU",$page)){
                $options .= '<a class="btn btn-primary btn-round m-5" onclick="edit_data('.$key->user_id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> Edit</a> ';
            }

            if (array_search("MDTCD",$page)){
                $options .= '<a href="javascript:;" class="btn btn-danger btn-round m-5" onclick="delete_data('.$key->user_id.')"><i class="fa fa-trash-o"></i> Delete</a>';
            }

            if (array_search("MDTCS",$page)){
                if($key->status == 0){
                    $options .= '<a class="btn btn-warning btn-round m-5" onclick="change_status('.$key->user_id.')"><i class="fa fa-power-off"></i> Non-Active</a> ';
                }else{
                    $options .= '<a class="btn btn-success btn-round m-5" onclick="change_status('.$key->user_id.')"><i class="fa fa-power-off"></i> Active</a> ';
                }
            }

            $detail->put('no', $i++);
            $detail->put('teacher_name', $key->teacher->name);
            $detail->put('teacher_subjects', $subjects);
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

    public static function setData($user_id, $courses_id){
        $old_list = Teacher::where('user_id', $user_id)->get();
        if($old_list->count() != 0){
            foreach($old_list as $list){
                $status = 0;
                foreach($courses_id as $course){
                    if($list->id == $course){
                        $status++;
                    }
                    if(Teacher::where('user_id', $user_id)->where('course_id', $course)->count() == 0){
                        $new = new Teacher(array(
                            "user_id" => $user_id,
                            "course_id" => $course,
                            "status" => 0,
                        ));
                        $new->save();
                    }
                }
                if($status == 0){
                    if(Teacher::where('user_id', $user_id)-> where('course_id', $course)->count() != 0){
                        $log_id = Log::setLog('MDTCD','Delete Teachers Subject : '.$list->get_course->name.' ('.$list->get_course->grade.')');
                        RecycleBin::moveToRecycleBin($log_id, $list->getTable(), json_encode($list));
                        $list->delete();
                    }
                }
            }
        }else{
            foreach($courses_id as $course){
                if(Teacher::where('user_id', $user_id)->where('course_id', $course)->count() == 0){
                    $new = new Teacher(array(
                        "user_id" => $user_id,
                        "course_id" => $course,
                        "status" => 0,
                    ));
                    $new->save();
                }
            }
        }

    }
}
