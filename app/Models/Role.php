<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Role extends Model
{
    use SoftDeletes;
    protected $table ='role';
    protected $fillable = [
        'name','description','creator'
    ];

    public static function dataIndex(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $page = MenuMapping::getMap(session('role_id'),"USRL");
        $role = Role::select('id','name','description');

        $totalRecords = $role->count();

        if($searchValue != ''){
            $role->where(function ($query) use ($searchValue) {
                $query->orWhere('name', 'LIKE', '%'.$searchValue.'%')->orWhere('description', 'LIKE', '%'.$searchValue.'%');
            });
        }

        $totalRecordwithFilter = $role->count();

        if($columnName == "no"){
            $role->orderBy('id', $columnSortOrder);
        }else{
            $role->orderBy($columnName, $columnSortOrder);
        }

        $role = $role->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($role as $key){
            $detail = collect();

            $options = '';

            if (array_search("USRLU",$page)){
                $options .= '<a class="btn btn-primary btn-round m-5" onclick="edit_data('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> Edit</a> ';
            }

            if (array_search("USRLD",$page)){
                $options .= '<a href="javascript:;" class="btn btn-danger btn-round m-5" onclick="delete_data('.$key->id.')"><i class="fa fa-trash-o"></i> Delete</a>';
            }

            $detail->put('no', $i++);
            $detail->put('name', $key->name);
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
