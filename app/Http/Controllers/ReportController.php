<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\ApiFormatter;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Order;
use App\Models\OrderPayment;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            if($request->type == "ongoing-order"){
                $datas = Order::dataOngoingDashboard($request);
                echo json_encode($datas);
            }
        }else{
            $page = "SRRP";
            return view('dashboard.statsreport.report',compact('page'));
        }
    }

    public function OrderReport(){
        $data = Order::orderReport();
        if($data != NULL){
            return ApiFormatter::createApi(Response::HTTP_OK, 'Success', $data);
        }else{
            return ApiFormatter::createApi(Response::HTTP_BAD_REQUEST, 'Failed');
        }
    }

    public function IncomeReport(){
        $data = OrderPayment::incomeReport();
        if($data != NULL){
            return ApiFormatter::createApi(Response::HTTP_OK, 'Success', $data);
        }else{
            return ApiFormatter::createApi(Response::HTTP_BAD_REQUEST, 'Failed');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
