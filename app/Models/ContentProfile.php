<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ContentProfile extends Model
{
    protected $table ='content_profile';
    protected $fillable = [
        'title','content','status','creator'
    ];

    public static function dataIndex(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $page = MenuMapping::getMap(session('role_id'),"CTCP");
        $profile = ContentProfile::select('id','title','content','status');

        $totalRecords = $profile->count();

        if($searchValue != ''){
            $profile->where(function ($query) use ($searchValue) {
                $query->orWhere('title', 'LIKE', '%'.$searchValue.'%')->orWhere('content', 'LIKE', '%'.$searchValue.'%');
            });
        }

        $totalRecordwithFilter = $profile->count();

        if($columnName == "no"){
            $profile->orderBy('id', $columnSortOrder);
        }else{
            $profile->orderBy($columnName, $columnSortOrder);
        }

        $profile = $profile->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($profile as $key){
            $detail = collect();

            $options = '';

            if (array_search("CTCPU",$page)){
                $options .= '<a class="btn btn-primary btn-round m-5" onclick="edit_data('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> Edit</a> ';
            }

            if (array_search("CTCPS",$page)){
                if($key->status == 0){
                    $options .= '<a class="btn btn-warning btn-round m-5" onclick="change_status('.$key->id.')"><i class="fa fa-power-off"></i> Non-Active</a> ';
                }else{
                    $options .= '<a class="btn btn-success btn-round m-5" onclick="change_status('.$key->id.')"><i class="fa fa-power-off"></i> Active</a> ';
                }
            }

            $detail->put('no', $i++);
            $detail->put('title', $key->title);
            $detail->put('content', $key->content);
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
