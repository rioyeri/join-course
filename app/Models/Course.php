<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Course extends Model
{
    protected $table ='course';
    protected $fillable = [
        'name','topic','description','status','creator'
    ];

    public static function dataIndex(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $page = MenuMapping::getMap(session('role_id'),"MDCO");
        $course = Course::select('id','name','topic','description','status');

        $totalRecords = $course->count();

        if($searchValue != ''){
            $course->where(function ($query) use ($searchValue) {
                $query->orWhere('name', 'LIKE', '%'.$searchValue.'%')->orWhere('topic', 'LIKE', '%'.$searchValue.'%')->orWhere('description', 'LIKE', '%'.$searchValue.'%');
            });
        }

        $totalRecordwithFilter = $course->count();

        if($columnName == "no"){
            $course->orderBy('id', $columnSortOrder);
        }else{
            $course->orderBy($columnName, $columnSortOrder);
        }

        $course = $course->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($course as $key){
            $detail = collect();

            $options = '';

            if (array_search("MDCOU",$page)){
                $options .= '<a class="btn btn-primary btn-round m-5" onclick="edit_data('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> Edit</a> ';
            }

            if (array_search("MDCOS",$page)){
                if($key->status == 0){
                    $options .= '<a class="btn btn-warning btn-round m-5" onclick="change_status('.$key->id.')"><i class="fa fa-power-off"></i> Non-Active</a> ';
                }else{
                    $options .= '<a class="btn btn-success btn-round m-5" onclick="change_status('.$key->id.')"><i class="fa fa-power-off"></i> Active</a> ';
                }
            }

            if (array_search("MDCOD",$page)){
                $options .= '<a href="javascript:;" class="btn btn-danger btn-round m-5" onclick="delete_data('.$key->id.')"><i class="fa fa-trash-o"></i> Delete</a>';
            }

            $detail->put('no', $i++);
            $detail->put('name', $key->name);
            $detail->put('topic', $key->topic);
            $detail->put('description', $key->description);
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
