<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Package extends Model
{
    use SoftDeletes;
    protected $table ='package';
    protected $fillable = [
        'name','description','status','creator'
    ];

    public static function dataIndex(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $page = MenuMapping::getMap(session('role_id'),"MDPC");
        $package = Package::select('id','name','description','status');

        $totalRecords = $package->count();

        if($searchValue != ''){
            $package->where(function ($query) use ($searchValue) {
                $query->orWhere('name', 'LIKE', '%'.$searchValue.'%')->orWhere('description', 'LIKE', '%'.$searchValue.'%');
            });
        }

        $totalRecordwithFilter = $package->count();

        if($columnName == "no"){
            $package->orderBy('id', $columnSortOrder);
        }else{
            $package->orderBy($columnName, $columnSortOrder);
        }

        $package = $package->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($package as $key){
            $detail = collect();

            $options = '';

            if (array_search("MDPCU",$page)){
                $options .= '<a class="btn btn-primary btn-round m-5" onclick="edit_data('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> Edit</a> ';
            }

            if (array_search("MDPCD",$page)){
                $options .= '<a href="javascript:;" class="btn btn-danger btn-round m-5" onclick="delete_data('.$key->id.')"><i class="fa fa-trash-o"></i> Delete</a>';
            }

            if (array_search("MDPCS",$page)){
                if($key->status == 0){
                    $options .= '<a class="btn btn-warning btn-round m-5" onclick="change_status('.$key->id.')"><i class="fa fa-power-off"></i> Non-Active</a> ';
                }else{
                    $options .= '<a class="btn btn-success btn-round m-5" onclick="change_status('.$key->id.')"><i class="fa fa-power-off"></i> Active</a> ';
                }
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
