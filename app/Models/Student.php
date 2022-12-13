<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Student extends Model
{
    use SoftDeletes;
    protected $table ='student';
    protected $fillable = [
        'user_id','student_grade','school_name'
    ];

    public function get_grade(){
        return $this->belongsTo('App\Models\Grade', 'student_grade', 'id');
    }

    public function student(){
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

        $page = MenuMapping::getMap(session('role_id'),"MDST");
        $student = Student::join('users as u', 'student.user_id', 'u.id')->select('student.id','u.name as student_name','student_grade','school_name','u.birthdate as birthdate','u.id as user_id');

        $totalRecords = $student->count();

        if($searchValue != ''){
            $student->where(function ($query) use ($searchValue) {
                // $user_id = array();
                // $users = User::all();
                // foreach($users as $user){
                //     if(User::getAge($user->birthdate) == $searchValue){
                //         array_push($user_id, $user->id);
                //     }
                // }
                // $query->orWhere('u.name', 'LIKE', '%'.$searchValue.'%')->orWhere('student_grade', 'LIKE', '%'.$searchValue.'%')->orWhere('school_name', 'LIKE', '%'.$searchValue.'%')->orWhereIn('user_id', $user_id);
                $query->orWhere('u.name', 'LIKE', '%'.$searchValue.'%')->orWhere('student_grade', 'LIKE', '%'.$searchValue.'%')->orWhere('school_name', 'LIKE', '%'.$searchValue.'%');
            });
        }

        $totalRecordwithFilter = $student->count();

        if($columnName == "no"){
            $student->orderBy('id', $columnSortOrder);
        }elseif($columnName == "student_age"){
            $student->orderBy('birthdate', $columnSortOrder);
        }else{
            $student->orderBy($columnName, $columnSortOrder);
        }

        $student = $student->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($student as $key){
            $detail = collect();

            $options = '';

            if (array_search("MDSTU",$page)){
                $options .= '<a class="btn btn-primary btn-round m-5" onclick="edit_data('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> Edit</a> ';
            }

            if (array_search("MDSTD",$page)){
                $options .= '<a href="javascript:;" class="btn btn-danger btn-round m-5" onclick="delete_data('.$key->id.')"><i class="fa fa-trash-o"></i> Delete</a>';
            }

            if($key->birthdate == null){
                $age = "<i>Unknown Birthdate</i>";
            }else{
                $age = User::getAge($key->birthdate)." years old";
            }

            $detail->put('no', $i++);
            $detail->put('student_name', $key->student_name);
            $detail->put('student_age', $age);
            $detail->put('student_grade', $key->get_grade->name);
            $detail->put('school_name', $key->school_name);
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

    public static function setData($user_id, $school_name, $student_grade){
        if(Student::where('user_id', $user_id)->count() != 0){
            $student = Student::where('user_id', $user_id)->first();
            $student->school_name = $school_name;
            $student->student_grade = $student_grade;
            $student->save();
        }else{
            $student = new Student(array(
                'user_id' => $user_id,
                'school_name' => $school_name,
                'student_grade' => $student_grade,
            ));
            $student->save();
        }

        return $student;
    }
}
