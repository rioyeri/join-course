<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\PaymentAccount;
use App\Models\Student;
use App\Models\MenuMapping;
use App\Models\Log;
use App\Models\RecycleBin;

class OrderPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $datas = OrderPayment::dataIndex($request);
            echo json_encode($datas);
        }else{
            $page = "ORPY";
            $submoduls = MenuMapping::getMap(session('role_id'),$page);
            return view('dashboard.order.payment.index',compact('page','submoduls'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(session('role_id') == 4){
            $student = Student::where('user_id', session('user_id'))->first();
            $orders = Order::where('student_id', $student->id)->get();
        }else{
            $orders = Order::all();
        }
        $accounts = PaymentAccount::all();
        return response()->json(view('dashboard.order.payment.form', compact('orders','accounts'))->render());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // die;
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'payment_amount' => 'required',
            'payment_method' => 'required',
            'payment_evidence' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $order = Order::where('id', $request->order_id)->first();
                
                if($request->order_token == $order->order_token){
                    $user_id = $order->get_student->user_id;
                }else{
                    $user_id = session('user_id');
                }
                $data = new OrderPayment(array(
                    "order_id" => $request->order_id,
                    "payment_amount" => $request->payment_amount,
                    "payment_method" => $request->payment_method,
                    "payment_confirmation" => 0,
                    "creator" => $user_id,
                ));
                $data->save();

                $invoice_id = OrderPayment::generateInvoiceID($data->id);
                // Upload Evidence
                if($request->payment_evidence <> NULL|| $request->payment_evidence <> ''){
                    $path = 'dashboard/assets/payment/';
                    $new_path = $path.$order->order_id;
                    if(!file_exists($new_path)){
                        mkdir($new_path);
                    }
                    $payment_evidence = $invoice_id.'.'.$request->payment_evidence->getClientOriginalExtension();
                    $request->payment_evidence->move(public_path($new_path),$payment_evidence);
                }

                $data->payment_evidence = $payment_evidence;
                $data->invoice_id = $invoice_id;
                $data->save();

                // Check Paid Off;
                OrderPayment::checkPaid($order->id);

                Log::setLog('ORPYC','Create Order Payment : '.$invoice_id.' for '.$order->order_id);
                return redirect()->route('orderpayment.index')->with('status','Successfully saved');
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = OrderPayment::where('id', $id)->first();
        $current_order_bill = Order::where('id', $data->order_id)->first()->order_bill;
        $orders = Order::all();
        $accounts = PaymentAccount::all();
        return response()->json(view('dashboard.order.payment.form', compact('data','orders','accounts','current_order_bill'))->render());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'payment_amount' => 'required',
            'payment_method' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $data = OrderPayment::where('id', $id)->first();
                $old_order = Order::where('id', $data->order_id)->first();
                $new_order = Order::where('id', $request->order_id)->first();

                // Upload Evidence
                if($request->payment_evidence <> NULL|| $request->payment_evidence <> ''){
                    $path = 'dashboard/assets/payment/';
                    $old_path = $path.$old_order->order_id.'/';
                    $new_path = $path.$new_order->order_id;

                    if(!file_exists($new_path)){
                        mkdir($new_path);
                    }

                    if (file_exists(public_path($old_path.$data->payment_evidence)) && $data->payment_evidence != null) {
                        unlink(public_path($old_path.$data->payment_evidence));
                    }

                    $payment_evidence = $data->invoice_id.'.'.$request->payment_evidence->getClientOriginalExtension();
                    $request->payment_evidence->move(public_path($new_path),$payment_evidence);
                }else{
                    $payment_evidence = $data->payment_evidence;
                }

                $data->order_id = $request->order_id;
                $data->payment_amount = $request->payment_amount;
                $data->payment_method = $request->payment_method;
                $data->payment_evidence = $payment_evidence;
                $data->payment_confirmation = 0;
                $data->creator = session('user_id');
                $data->save();


                if($old_order->order_id != $new_order->order_id){
                    // Check Paid Off;
                    OrderPayment::checkPaid($old_order->id);
                    OrderPayment::checkPaid($new_order->id);
                    Log::setLog('ORPYU','Update Order Payment '.$data->invoice_id.' for '.$old_order->order_id.' to '.$new_order->order_id);
                }else{
                    OrderPayment::checkPaid($data->order_id);
                    Log::setLog('ORPYU','Update Order Payment '.$data->invoice_id.' for '.$old_order->order_id);
                }
                return redirect()->route('orderpayment.index')->with('status','Successfully saved');
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $data = OrderPayment::where('id', $id)->first();
            $order_id = $data->order_id;
            $path = 'dashboard/assets/payment/'.$data->get_order->order_id.'/';
            $recycle_bin_path = 'dashboard/assets/recyclebin/';
            if (file_exists(public_path($path.$data->payment_evidence)) && $data->payment_evidence != null) {
                rename(public_path($path.$data->payment_evidence), public_path($recycle_bin_path.$data->payment_evidence));
            }
            $log_id = Log::setLog('ORPYD','Delete Order Payment : '.$data->invoice_id);
            RecycleBin::moveToRecycleBin($log_id, $data->getTable(), json_encode($data));
            $data->delete();
            OrderPayment::checkPaid($order_id);
            return "true";
        }catch(\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function changeStatus(Request $request, $id){
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $payment = OrderPayment::where('id', $id)->first();

                if($payment->payment_confirmation == 0){
                    $new_status = 1;
                    $text_log = 'Confirming Payment : '.$payment->invoice_id.' for '.$payment->get_order->order_id;
                }else{
                    $new_status = 0;
                    $text_log = 'Cancel confirm Payment : '.$payment->invoice_id.' for '.$payment->get_order->order_id;
                }

                $payment->payment_confirmation = $new_status;
                $payment->save();

                Log::setLog('ORPYS', $text_log);
                return "true";
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    public function paymentOrderPage(Request $request, $order_id, $token){
        $count_data = Order::where('order_id', $order_id)->where('order_token', $token)->count();
        if($request->session()->has('user_id')){
            $request->session()->put('order_id', $order_id);
            $request->session()->put('order_token', $token);
            return redirect()->route('orderpayment.index');
        }else{
            if($count_data != 0){
                $data = Order::where('order_id', $order_id)->where('order_token', $token)->first();
                $accounts = PaymentAccount::all();
                return view('dashboard.order.payment.single-form', compact('data','accounts'));
            }else{
                $order_id = $order_id;
                return view('dashboard.order.payment.payment-not-found',compact('order_id'));
            }
        }
    }
}
