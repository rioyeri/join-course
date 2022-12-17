<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class PaymentAccount extends Model
{
    use SoftDeletes;
    protected $table ='payment_account';
    protected $fillable = [
        'account_type','account_name','account_number','status','creator'
    ];

    public function get_bank(){
    return $this->belongsTo('App\Models\Bank','account_type','name');
    }

    public function creator(){
        return $this->belongsTo('App\Models\User','creator','id');
    }

    public static function dataIndex(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $page = MenuMapping::getMap(session('role_id'),"MDPA");
        $account = PaymentAccount::join('users as u', 'payment_account.creator', 'u.id')->select('payment_account.id','account_type','account_number','account_name','status','creator','u.name AS creator_name');

        $totalRecords = $account->count();

        if($searchValue != ''){
            $account->where(function ($query) use ($searchValue) {
                $query->orWhere('account_type', 'LIKE', '%'.$searchValue.'%')->orWhere('account_number', 'LIKE', '%'.$searchValue.'%')->orWhere('account_name', 'LIKE', '%'.$searchValue.'%')->orWhere('u.name', 'LIKE', '%'.$searchValue.'%');
            });
        }

        $totalRecordwithFilter = $account->count();

        if($columnName == "no"){
            $account->orderBy('id', $columnSortOrder);
        }else{
            $account->orderBy($columnName, $columnSortOrder);
        }

        $account = $account->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($account as $key){
            $detail = collect();

            $options = '';

            if (array_search("MDPAU",$page)){
                $options .= '<a class="btn btn-primary btn-round m-5" onclick="edit_data('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> Edit</a> ';
            }

            if (array_search("MDPAD",$page)){
                $options .= '<a href="javascript:;" class="btn btn-danger btn-round m-5" onclick="delete_data('.$key->id.')"><i class="fa fa-trash-o"></i> Delete</a>';
            }

            if (array_search("MDPAS",$page)){
                if($key->status == 0){
                    $options .= '<a class="btn btn-warning btn-round m-5" onclick="change_status('.$key->id.')"><i class="fa fa-power-off"></i> Non-Active</a> ';
                }else{
                    $options .= '<a class="btn btn-success btn-round m-5" onclick="change_status('.$key->id.')"><i class="fa fa-power-off"></i> Active</a> ';
                }
            }

            $detail->put('no', $i++);
            $detail->put('account_type', $key->account_type);
            $detail->put('account_number', $key->account_number);
            $detail->put('account_name', $key->account_name);
            $detail->put('creator_name', $key->creator_name);
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