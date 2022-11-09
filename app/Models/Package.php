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

    public static function getPackageStats($month=null){
        $data = collect();
        $periodic = "";
        if($month != null){
            $start = date('Y-'.$month.'-01'); // hard-coded '01' for first day
            $end  = date('Y-'.$month.'-t');
            $periodic = date('M Y');    
        }
        $colors = Color::getColor()->shuffle();
        $i=0;

        foreach(Package::where('status', 1)->get() as $key){
            $temp = collect();
            if($month != null){
                $count_orders = Order::whereIn('order_status', [1,2])->whereDate('created_at', ">=", $start)->whereDate('created_at', "<=", $end)->where('package_id', $key->id)->count();
            }else{
                $count_orders = Order::whereIn('order_status', [1,2])->where('package_id', $key->id)->count();
            }

            if($count_orders != 0){
                $temp->put('package_name', $key->name);
                $temp->put('order_qty', $count_orders);
                $temp->put('color', $colors[$i]);
                $temp->put('month_name', $periodic);
                $data->push($temp);
                if($i++ <= 9){
                    $i++;
                }else{
                    $i=0;
                }
            }
        }

        if(count($data) > 10){
            $qtys = array();
            foreach ($data as $key => $row){
                $qtys[$key] = $row['order_qty'];
            }
            array_multisort($qtys, SORT_DESC, $data);
    
            $result = collect();
            for($i=0; $i<10; $i++){
                $result->push($data[$i]);
            }
        }else{
            $result = $data;
        }

        return $result;
    }
}
