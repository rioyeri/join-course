<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Bank;
use App\Models\PaymentAccount;
use App\Models\MenuMapping;
use App\Models\Log;
use App\Models\RecycleBin;

class PaymentAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $datas = PaymentAccount::dataIndex($request);
            echo json_encode($datas);
        }else{
            $page = "MDPA";
            $submoduls = MenuMapping::getMap(session('role_id'),$page);
            return view('dashboard.masterdata.payment-account.index',compact('page','submoduls'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $account_types = Bank::all();
        return response()->json(view('dashboard.masterdata.payment-account.form', compact('account_types'))->render());
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
            'account_type' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $data = new PaymentAccount(array(
                    "account_type" => $request->account_type,
                    "account_name" => $request->account_name,
                    "account_number" => $request->account_number,
                    "status" => 0,
                    "creator" => session('user_id'),
                ));
                $data->save();
                Log::setLog('MDPAC','Create Payment Account : '.$request->account_number);
                return redirect()->route('paymentaccount.index')->with('status','Successfully saved');
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
        $account_types = Bank::all();
        $data = PaymentAccount::where('id', $id)->first();
        return response()->json(view('dashboard.masterdata.payment-account.form',compact('data','account_types'))->render());
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
            'account_type' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                PaymentAccount::where('id', $id)->update(array(
                    "account_type" => $request->account_type,
                    "account_name" => $request->account_name,
                    "account_number" => $request->account_number,
                    "creator" => session('user_id'),
                ));
                Log::setLog('MDPAU','Update Payment Account : '.$request->account_number);
                return redirect()->route('paymentaccount.index')->with('status','Successfully saved');
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
            $data = PaymentAccount::where('id', $id)->first();
            $log_id = Log::setLog('MDPAD','Delete Payment Account : '.$data->account_number);
            RecycleBin::moveToRecycleBin($log_id, $data->getTable(), json_encode($data));
            $data->delete();
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
                $data = PaymentAccount::where('id', $id)->first();
                if($data->status == 0){
                    $new_status = 1;
                    $text_log = "Activate Payment Account : ".$data->account_number;
                }else{
                    $new_status = 0;
                    $text_log = "Deactivate Payment Account : ".$data->account_number;
                }
                $data->status = $new_status;
                $data->creator = session('user_id');
                $data->save();
                Log::setLog('MDPAS', $text_log);
                return "true";
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }
}
