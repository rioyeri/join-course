<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TeacherSchedule extends Model
{
    protected $table ='teacher_schedule';
    protected $fillable = [
        'teacher_id','day_id','time_start','time_end','creator'
    ];

    public function get_day(){
        return $this->belongsTo('App\Models\Day', 'day_id', 'id');
    }

    public static function dataIndex(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $page = MenuMapping::getMap(session('role_id'),"CTTS");
        $teacher_id = TeacherSchedule::select('teacher_id')->get();
        $teacher = Teacher::join('users as u', 'teacher.user_id', 'u.id')->whereIn('teacher.id', $teacher_id)->select('teacher.id', 'u.name as teacher_name');

        $totalRecords = $teacher->count();

        if($searchValue != ''){
            $teacher->where(function ($query) use ($searchValue) {
                // $query->orWhere('u.name', 'LIKE', '%'.$searchValue.'%')->orWhere('d.nama_hari', 'LIKE', '%'.$searchValue.'%')->orWhere('d.day_name', 'LIKE', '%'.$searchValue.'%')->orWhere('time_start', 'LIKE', '%'.$searchValue.'%')->orWhere('time_end', 'LIKE', '%'.$searchValue.'%');
                $days = Day::select('id')->where(function ($query2) use ($searchValue) {
                    $query2->orWhere('day_name', 'LIKE', '%'.$searchValue.'%')->orWhere('nama_hari', 'LIKE', '%'.$searchValue.'%');
                })->get();
                // echo "<pre>";
                // print_r($days);
                // die;
                $schedule = TeacherSchedule::select('teacher_id')->where(function ($query3) use ($searchValue, $days) {
                    $query3->orWhereIn('day_id', $days)->orWhere('time_start', 'LIKE', '%'.$searchValue.'%')->orWhere('time_end', 'LIKE', '%'.$searchValue.'%');
                })->get();
                $query->orWhere('u.name', 'LIKE', '%'.$searchValue.'%')->orWhereIn('teacher.id', $schedule);
            });
        }

        $totalRecordwithFilter = $teacher->count();

        if($columnName == "no"){
            $teacher->orderBy('teacher.id', $columnSortOrder);
        }else{
            $teacher->orderBy($columnName, $columnSortOrder);
        }

        $teacher = $teacher->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($teacher as $key){
            $detail = collect();

            $schedules = TeacherSchedule::where('teacher_id', $key->id)->get();
            $teacher_schedules = '';
            foreach($schedules as $schedule){
                $teacher_schedules .= '<li>'.$schedule->get_day->day_name.', '.$schedule->time_start.' - '.$schedule->time_end.'</li>';
            }

            $options = '';
            if (array_search("CTTSU",$page)){
                $options .= '<a class="btn btn-primary btn-round m-5" onclick="edit_data('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> Edit</a> ';
            }

            if (array_search("CTTSD",$page)){
                $options .= '<a href="javascript:;" class="btn btn-danger btn-round m-5" onclick="delete_data('.$key->id.')"><i class="fa fa-trash-o"></i> Delete</a>';
            }

            $detail->put('no', $i++);
            $detail->put('teacher_name', $key->teacher_name);
            $detail->put('teacher_schedules', $teacher_schedules);
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

    public static function setData($id, $day_ids, $time_starts, $time_ends){
        $teacher = Teacher::where('id', $id)->first();

        if(TeacherSchedule::where('teacher_id', $id)->count() != 0){
            TeacherSchedule::where('teacher_id', $id)->delete();
        }

        for($i=0; $i < count($day_ids); $i++){
            $data = new TeacherSchedule(array(
                "teacher_id" => $id,
                "day_id" => $day_ids[$i],
                "time_start" => $time_starts[$i],
                "time_end" => $time_ends[$i],
                "creator" => session('user_id'),
            ));
            $data->save();
        }
        Log::setLog('CTTSU','Update Teacher Schedule : '.$teacher->teacher->name);
    }
}
