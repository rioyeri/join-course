<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ContentPromo extends Model
{
    protected $table ='content_promo';
    protected $fillable = [
        'package_id','icon','price','time_signature','link_text','link','category','creator','position'
    ];

    public function get_package(){
        return $this->belongsTo('App\Models\Package', 'package_id', 'id');
    }

    public static function dataIndex(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $page = MenuMapping::getMap(session('role_id'),"CTPR");
        $promos = ContentPromo::join('users as u', 'content_promo.creator', 'u.id')->join('package as p', 'content_promo.package_id', 'p.id')->select('content_promo.id','content_promo.package_id','p.name as package_name','icon','p.price','p.discount_rate', DB::raw('p.price - (p.price / 100 * p.discount_rate) as discount_price'),'link_text','category', 'content_promo.creator', 'u.name as creator_name','content_promo.position');

        $totalRecords = $promos->count();

        if($searchValue != ''){
            $promos->where(function ($query) use ($searchValue) {
                $promo_ids = ContentPromoDetail::select('promo_id')->where('text', 'LIKE', '%'.$searchValue.'%')->get();
                $price_ids = Package::select('id')->where(DB::raw('price - (price / 100 * discount_rate)'), 'LIKE', '%'.$searchValue.'%')->orWhere('discount_rate', 'LIKE', '%'.$searchValue.'%')->get();
                $query->orWhere('p.name', 'LIKE', '%'.$searchValue.'%')->orWhere('p.price', 'LIKE', '%'.$searchValue.'%')->orWhere('link_text', 'LIKE', '%'.$searchValue.'%')->orWhereIn('content_promo.id', $promo_ids)->orWhereIn('content_promo.package_id', $price_ids);
            });
        }

        $totalRecordwithFilter = $promos->count();

        if($columnName == "no"){
            $promos->orderBy('position', $columnSortOrder);
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

            $detail->put('no', $key->position.' <i class="fa fa-sort"></i>');
            $detail->put('package_name', $key->package_name);
            $detail->put('icon', $key->icon);
            $detail->put('price', $key->price);
            $detail->put('discount_rate', $key->discount_rate);
            $detail->put('discount_price', $key->discount_price);
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
        $promos = ContentPromo::join('package as p', 'content_promo.package_id', 'p.id')->select('content_promo.id', 'content_promo.package_id', 'p.name as package_name', 'icon', 'p.price', 'p.discount_rate', 'link_text', 'link', 'category')->orderBy('position', 'asc')->get();
        
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

            $disc_price = $promo->price - ($promo->price/100*$promo->discount_rate);

            $row->put('package_name', $promo->package_name);
            $row->put('icon', $promo->icon);
            $row->put('price', $promo->price);
            $row->put('discount_rate', $promo->discount_rate);
            $row->put('discount_price', $disc_price);
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
