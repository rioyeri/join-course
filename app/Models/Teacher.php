<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Teacher extends Model
{
    protected $table ='teacher';
    protected $fillable = [
        'user_id','title','description','location','status'
    ];

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
        $teachers = Teacher::join('users as u', 'teacher.user_id', 'u.id')->select('teacher.id','u.name as teacher_name', 'teacher.user_id', 'title', 'description', 'location','status');

        $totalRecords = $teachers->count();

        if($searchValue != ''){
            $teachers->where(function ($query) use ($searchValue) {
                $query->orWhere('u.name', 'LIKE', '%'.$searchValue.'%')->orWhere('title', 'LIKE', '%'.$searchValue.'%')->orWhere('description', 'LIKE', '%'.$searchValue.'%')->orWhere('location', 'LIKE', '%'.$searchValue.'%');
            });
        }

        $totalRecordwithFilter = $teachers->count();

        if($columnName == "no"){
            $teachers->orderBy('updated_at', $columnSortOrder);
        }else{
            $teachers->orderBy($columnName, $columnSortOrder);
        }

        $teachers = $teachers->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($teachers as $key){
            $detail = collect();
            $profile_button = '';
            $courses_button = '';
            $prices_button = '';
 
            if (array_search("MDTCV",$page)){
                if(TeacherPrice::where('teacher_id', $key->id)->count() != 0){
                    $count_price = TeacherPrice::where('teacher_id', $key->id)->count();
                    $color_price = "#000";
                }else{
                    $count_price = "Empty";
                    $color_price = "#FF0000";
                }

                if(TeacherCourse::where('teacher_id', $key->id)->count() != 0){
                    $count_course = TeacherCourse::where('teacher_id', $key->id)->count();
                    $color_course = "#000";
                }else{
                    $count_course = "Empty";
                    $color_course = "#FF0000";
                }
                $profile_button .= '<a class="btn btn-info m-5" onclick="view_profile('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-user"></i> View Profile</a> ';
                $courses_button .= '<a class="btn btn-info m-5" onclick="view_subject('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-list-ul"></i> View Subjects <span style="color:'.$color_course.'">('.$count_course.')</span></a> ';
                $prices_button .= '<a class="btn btn-info m-5" onclick="view_price('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-usd"></i> View Packages <span style="color:'.$color_price.'">('.$count_price.')</span></a> ';
            }

            $options = '';
            if (array_search("MDTCD",$page)){
                $options .= '<a href="javascript:;" class="btn btn-danger btn-round m-5" onclick="delete_data('.$key->id.')"><i class="fa fa-trash-o"></i> Delete</a> ';
            }

            if (array_search("MDTCS",$page)){
                if($key->status == 0){
                    $options .= '<a class="btn btn-warning btn-round m-5" onclick="change_status('.$key->id.')"><i class="fa fa-power-off"></i> Non-Active</a> ';
                }else{
                    $options .= '<a class="btn btn-success btn-round m-5" onclick="change_status('.$key->id.')"><i class="fa fa-power-off"></i> Active</a> ';
                }
            }

            $detail->put('no', $i++);
            $detail->put('teacher_name', $key->teacher->name);
            $detail->put('title', $key->title);
            $detail->put('teacher_profile', $profile_button);
            $detail->put('courses', $courses_button);
            $detail->put('prices',$prices_button);
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
}