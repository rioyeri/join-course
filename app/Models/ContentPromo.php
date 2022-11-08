<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ContentPromo extends Model
{
    protected $table ='content_promo';
    protected $fillable = [
        'name','icon','price','time_signature','link_text','link','category','creator'
    ];

    public static function dataIndex(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $page = MenuMapping::getMap(session('role_id'),"CTPR");
        $promos = ContentPromo::join('users as u', 'content_promo.creator', 'u.id')->select('content_promo.id','content_promo.name','icon','price','time_signature','link_text','link','category', 'content_promo.creator', 'u.name as creator_name');

        $totalRecords = $promos->count();

        if($searchValue != ''){
            $promos->where(function ($query) use ($searchValue) {
                $promo_ids = ContentPromoDetail::select('promo_id')->where('text', 'LIKE', '%'.$searchValue.'%')->get();
                $query->orWhere('name', 'LIKE', '%'.$searchValue.'%')->orWhere('price', 'LIKE', '%'.$searchValue.'%')->orWhere('time_signature', 'LIKE', '%'.$searchValue.'%')->orWhere('link_text', 'LIKE', '%'.$searchValue.'%')->orWhere('link', 'LIKE', '%'.$searchValue.'%')->orWhereIn('id', $promo_ids);
            });
        }

        $totalRecordwithFilter = $promos->count();

        if($columnName == "no"){
            $promos->orderBy('id', $columnSortOrder);
        }else{
            $promos->orderBy($columnName, $columnSortOrder);
        }

        $promos = $promos->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($promos as $key){
            $detail = collect();

            if($key->category == 1){
                $category = "Best Offer";
            }else{
                $category = "Regular Offer";
            }
            $options = '';
            if (array_search("CTPRU",$page)){
                $options .= '<a class="btn btn-primary btn-round m-5" onclick="edit_data('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> Edit</a> ';
            }
            if (array_search("CTPRD",$page)){
                $options .= '<a href="javascript:;" class="btn btn-danger btn-round m-5" onclick="delete_data('.$key->id.')"><i class="fa fa-trash-o"></i> Delete</a>';
            }

            $detail->put('no', $i++);
            $detail->put('name', $key->name);
            $detail->put('icon', $key->icon);
            $detail->put('price', $key->price);
            $detail->put('time_signature', $key->time_signature);
            $detail->put('link_text', $key->link_text);
            $detail->put('category', $category);
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

    public static function getContent(){
        $result = collect();
        $promos = ContentPromo::all();
        
        $count_detail = $promos->count();
        if($count_detail == 3){
            $column_size = "col-lg-4";
        }elseif($count_detail == 4){
            $column_size = "col-lg-3";
        }elseif($count_detail == 5){
            $column_size = "col-lg-2";
        }else{
            $column_size = "col-lg-6";
        }

        foreach($promos as $promo){
            $row = collect();
            $row_detail = collect();
            $promodet = ContentPromoDetail::where('promo_id', $promo->id)->get();
            foreach($promodet as $det){
                $detail = collect();
                $detail->put('text', $det->text);
                $detail->put('status', $det->status);
                $row_detail->push($detail);
            }

            $row->put('name', $promo->name);
            $row->put('icon', $promo->icon);
            $row->put('price', $promo->price);
            $row->put('time_signature', $promo->time_signature);
            $row->put('link_text', $promo->link_text);
            $row->put('link', $promo->link);
            $row->put('category', $promo->category);
            $row->put('detail', $row_detail);
            $row->put('column_size_detail', $column_size);
            $result->push($row);
        }

        return json_decode(json_encode($result), FALSE);
    }
}
