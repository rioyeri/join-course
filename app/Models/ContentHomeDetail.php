<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ContentHomeDetail extends Model
{
    protected $table ='content_home_detail';
    protected $fillable = [
        'content_id','title','subtitle','description','image','link','link_text','creator'
    ];

    public static function dataIndex(Request $request){
        // echo "<pre>";
        // print_r($request->all());
        // die;
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value
        $id = $request->id;

        $page = MenuMapping::getMap(session('role_id'),"CTHO");
        $home = ContentHomeDetail::where('id', $id)->select('id','title','subtitle','description','creator');

        $totalRecords = $home->count();

        if($searchValue != ''){
            $home->where(function ($query) use ($searchValue) {
                $query->orWhere('description', 'LIKE', '%'.$searchValue.'%')->orWhere('title', 'LIKE', '%'.$searchValue.'%')->orWhere('subtitle', 'LIKE', '%'.$searchValue.'%');
            });
        }

        $totalRecordwithFilter = $home->count();

        if($columnName == "0"){
            $home->orderBy('id', $columnSortOrder);
        }else{
            $home->orderBy($columnName, $columnSortOrder);
        }

        $home = $home->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($home as $key){
            $detail = collect();

            $detail->put('id', $key->id);
            $detail->put('title', $key->title);
            $detail->put('subtitle', $key->subtitle);
            $detail->put('description', $key->description);
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
