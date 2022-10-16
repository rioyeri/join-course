<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\PaymentAccount;
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
        $orders = Order::all();
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
                $data = new OrderPayment(array(
                    "order_id" => $request->order_id,
                    "payment_amount" => $request->payment_amount,
                    "payment_method" => $request->payment_method,
                    "payment_evidence" => $request->payment_evidence,
                    "payment_confirmation" => 0,
                    "creator" => session('user_id'),
                ));
                $data->save();
                Log::setLog('MDPAC','Create Payment Order : '.$request->order_id);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
