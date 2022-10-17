<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class OrderPayment extends Model
{
    protected $table ='order_payment';
    protected $fillable = [
        'order_id','invoice_id','payment_amount','payment_method','payment_evidence','payment_confirmation','confirmation_by','creator'
    ];

    public function get_order(){
        return $this->belongsTo('App\Models\Order','order_id','id');
    }

    public function creator(){
        return $this->belongsTo('App\Models\User','creator','id');
    }

    public static function generateInvoiceID($id){
        $invoice_id = "#FAPAY".date('Ymd')."-".$id;
        return $invoice_id;
    }

    public static function dataIndex(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $page = MenuMapping::getMap(session('role_id'),"ORPY");
        $account = OrderPayment::join('users as u', 'order_payment.creator', 'u.id')->join('payment_account as pa', 'order_payment.payment_method', 'pa.id')->join('order as o', 'order_payment.order_id', 'o.id')->select('order_payment.id','order_payment.invoice_id','order_payment.order_id as order_fk','o.order_id','o.order_bill','payment_amount','payment_method','pa.account_number','pa.account_type','payment_evidence','order_payment.creator','u.name AS creator_name','payment_confirmation','confirmation_by','order_payment.created_at','order_payment.updated_at');

        $totalRecords = $account->count();

        if($searchValue != ''){
            $account->where(function ($query) use ($searchValue) {
                $query->orWhere('o.order_id', 'LIKE', '%'.$searchValue.'%')->orWhere('pa.account_type', 'LIKE', '%'.$searchValue.'%')->orWhere('payment_amount', 'LIKE', '%'.$searchValue.'%')->orWhere('order_payment.created_at', 'LIKE', '%'.$searchValue.'%')->orWhere('u.name', 'LIKE', '%'.$searchValue.'%');
            });
        }

        $totalRecordwithFilter = $account->count();

        if($columnName == "no"){
            $account->orderBy('id', $columnSortOrder);
        }elseif($columnName == "payment_time"){
            $account->orderBy('created_at', $columnSortOrder);
        }else{
            $account->orderBy($columnName, $columnSortOrder);
        }

        $account = $account->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($account as $key){
            $detail = collect();

            $payment_account = PaymentAccount::where('id', $key->payment_method)->first();
            $payment_method = $payment_account->account_type." ".$payment_account->account_number;


            if (array_search("ORPYS",$page)){
                if($key->payment_confirmation == 0){
                    $payment_confirmation = '<a class="btn btn-warning m-5" onclick="change_status('.$key->id.',0)"><i class="fa fa-dollar"></i> Not Confirmed Yet</a> ';
                }else{
                    $payment_confirmation = '<a class="btn btn-success m-5" onclick="change_status('.$key->id.',1)"><i class="fa fa-dollar"></i> Payment Confirmed</a> ';
                }
            }else{
                if($key->payment_confirmation == 0){
                    $payment_confirmation = '<a class="btn btn-warning m-5"><i class="fa fa-dollar"></i> Not Confirmed Yet</a> ';
                }else{
                    $payment_confirmation = '<a class="btn btn-success m-5"><i class="fa fa-dollar"></i> Payment Confirmed</a> ';
                }
            }

            $options = '';
            if (array_search("ORPYU",$page)){
                $options .= '<a class="btn btn-primary btn-round m-5" onclick="edit_data('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> Edit</a> ';
            }
            if (array_search("ORPYD",$page)){
                $options .= '<a href="javascript:;" class="btn btn-danger btn-round m-5" onclick="delete_data('.$key->id.')"><i class="fa fa-trash-o"></i> Delete</a>';
            }

            $detail->put('no', $i++);
            $detail->put('invoice_id', $key->invoice_id);
            $detail->put('order_id', $key->order_id);
            $detail->put('order_bill', $key->order_bill);
            $detail->put('payment_amount', $key->payment_amount);
            $detail->put('payment_method', $payment_method);
            $detail->put('payment_evidence', $key->payment_evidence);
            $detail->put('payment_time', $key->created_at->format('Y-m-d H:i'));
            $detail->put('payment_confirmation', $payment_confirmation);
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
