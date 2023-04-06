<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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

    public function get_paymentmethod(){
        return $this->belongsTo('App\Models\PaymentAccount', 'payment_method', 'id');
    }

    public static function generateInvoiceID($id){
        $invoice_id = "FAPAY".date('Ymd')."-".$id;
        return $invoice_id;
    }

    public static function checkPaid($order_id, $new_token=null){
        $order = Order::where('id', $order_id)->first();
        $payment = OrderPayment::where('order_id', $order_id)->where('payment_confirmation', 1)->sum('payment_amount');
        if($payment == $order->order_bill){
            $result = 1;
            $token = null;
            if(session()->has('order_id') && session()->has('order_token')){
                session()->forget('order_id');
                session()->forget('order_token');    
            }
            // $order_status = 1;
        }elseif($payment > $order->order_bill){
            $result = 2;
            $token = $order->order_token;
            // $order_status = 0;
        }else{
            $result = 0;
            $token = $new_token;
            // $order_status = 0;
        }
        $order->payment_status = $result;
        $order->order_token = $token;
        // $order->order_status = $order_status;
        $order->save();
    }

    public static function getRemainingPayment($order_id){
        $order = Order::where('id', $order_id)->first();
        $payment = OrderPayment::where('order_id', $order_id)->where('payment_confirmation', 1)->sum('payment_amount');
        $remainingPayment = $order->order_bill - $payment;

        return $remainingPayment;
    }

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

        $start = $request->start_date;
        $end = $request->end_date;

        $page = MenuMapping::getMap(session('role_id'),"ORPY");
        if(session('role_id') == 4 || session('role_id') == 5){
            $orderpayment = OrderPayment::join('users as u', 'order_payment.creator', 'u.id')->join('payment_account as pa', 'order_payment.payment_method', 'pa.id')->join('order as o', 'order_payment.order_id', 'o.id')->join('student as s', 'o.student_id', 's.id')->join('users as us', 's.user_id', 'us.id')->select('order_payment.id','order_payment.invoice_id','order_payment.order_id as order_fk','o.order_id','o.order_bill','us.name AS student_name','payment_amount','payment_method','pa.account_number','pa.account_type','payment_evidence','order_payment.creator','u.name AS creator_name','payment_confirmation','confirmation_by','order_payment.created_at','order_payment.updated_at')->where('us.id', session('user_id'));
        }else{
            $orderpayment = OrderPayment::join('users as u', 'order_payment.creator', 'u.id')->join('payment_account as pa', 'order_payment.payment_method', 'pa.id')->join('order as o', 'order_payment.order_id', 'o.id')->join('student as s', 'o.student_id', 's.id')->join('users as us', 's.user_id', 'us.id')->select('order_payment.id','order_payment.invoice_id','order_payment.order_id as order_fk','o.order_id','o.order_bill','us.name AS student_name','payment_amount','payment_method','pa.account_number','pa.account_type','payment_evidence','order_payment.creator','u.name AS creator_name','payment_confirmation','confirmation_by','order_payment.created_at','order_payment.updated_at');
        }

        if($start != "" && $end != ""){
            $orderpayment->whereBetween(DB::raw('DATE(order_payment.created_at)'), [$start,$end]);
        }elseif($start != "" && $end == ""){
            $orderpayment->whereDate('order_payment.created_at', '>=', $start);
        }elseif($start == "" && $end != ""){
            $orderpayment->whereDate('order_payment.created_at', '<=', $end);
        }

        if($request->type == "confirm"){
            $orderpayment->where('payment_confirmation', 1);
        }elseif($request->type == "decline"){
            $orderpayment->where('payment_confirmation', -1);
        }else{
            $orderpayment->where('payment_confirmation', 0);
        }

        $totalRecords = $orderpayment->count();

        if($searchValue != ''){
            $orderpayment->where(function ($query) use ($searchValue) {
                $query->orWhere('o.order_id', 'LIKE', '%'.$searchValue.'%')->orWhere('pa.account_type', 'LIKE', '%'.$searchValue.'%')->orWhere('payment_amount', 'LIKE', '%'.$searchValue.'%')->orWhere('order_payment.created_at', 'LIKE', '%'.$searchValue.'%')->orWhere('u.name', 'LIKE', '%'.$searchValue.'%')->orWhere('invoice_id', 'LIKE', '%'.$searchValue.'%');
            });
        }

        $totalRecordwithFilter = $orderpayment->count();

        if($columnName == "no"){
            $orderpayment->orderBy('id', $columnSortOrder);
        }elseif($columnName == "payment_time"){
            $orderpayment->orderBy('order_payment.created_at', $columnSortOrder);
        }else{
            $orderpayment->orderBy($columnName, $columnSortOrder);
        }

        $orderpayment = $orderpayment->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($orderpayment as $key){
            $detail = collect();

            $payment_account = PaymentAccount::where('id', $key->payment_method)->first();
            $payment_method = $payment_account->account_type." ".$payment_account->account_number;

            // $student_id = Order::where('id', $key->order_fk)->first()->student_id;
            // $student_name = Student::where('id', $student_id)->first()->student->name;

            if (array_search("ORPYS",$page)){
                if($key->payment_confirmation == 1){
                    $payment_confirmation = '<a class="btn btn-success btn-round m-5" onclick="change_status('.$key->id.',1)"><i class="fa fa-dollar"></i> Payment Confirmed</a> ';
                }elseif($key->payment_confirmation == -1){
                    $payment_confirmation = '<a class="btn btn-danger btn-round m-5" onclick="change_status('.$key->id.',-1)"><i class="fa fa-dollar"></i> Payment Decline</a> ';
                }else{
                    $payment_confirmation = '<a class="btn btn-warning btn-round m-5" onclick="change_status('.$key->id.',0)"><i class="fa fa-dollar"></i> Not Confirmed Yet</a> ';
                }
            }else{
                if($key->payment_confirmation == 1){
                    $payment_confirmation = '<a class="btn btn-success m-5"><i class="fa fa-dollar"></i> Payment Confirmed</a> ';
                }elseif($key->payment_confirmation == -1){
                    $payment_confirmation = '<a class="btn btn-danger m-5"><i class="fa fa-dollar"></i> Payment Decline</a> ';
                }else{
                    $payment_confirmation = '<a class="btn btn-warning m-5"><i class="fa fa-dollar"></i> Not Yet Confirmed</a> ';
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
            $detail->put('invoice_id', '#'.$key->invoice_id);
            $detail->put('order_id', '#'.$key->order_id);
            $detail->put('student_name', $key->student_name);
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

    public static function incomeReport(){
        $data = collect();
        $year  = date('Y');
        $i=0;

        for($month=1; $month<=12; $month++){
            $temp = collect();
            // $month_name = date('M');
            $month_name = date('Y-m', strtotime($year."-".$month."-01"));
            $count_order = OrderPayment::where('payment_confirmation', 1)->whereMonth('created_at', $month)->whereYear('created_at', $year)->sum('payment_amount');

            $temp->put('report_period', $month_name);
            $temp->put('report_count', $count_order);
            $data->push($temp);
            if($i < 9){
                $i++;
            }else{
                $i=0;
            }
        }
        return $data;
    }
}
