<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Order;
use App\Models\OrderReview;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Log;

class OrderReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->ajax()){
            $data = Order::where('id', $request->id)->first();
            $data_teacher = Teacher::where('id', $data->teacher_id)->first();
            $row = collect();
            $row->put('name', $data_teacher->teacher->name);
            $row->put('photo', User::getPhoto($data_teacher->user_id));
            $teacher = json_decode(json_encode($row), FALSE);
            return response()->json(view('dashboard.home.review', compact('data','teacher'))->render());
        }
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
            '_token' => 'required',
            'optionsRadios' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $order = Order::where('id', $request->order_id)->first();
                if(OrderReview::where('order_id', $request->order_id)->count() != 0){
                    $data = OrderReview::where('order_id', $request->order_id)->first();
                    $data->teacher_id = $request->teacher_id;
                    $data->rating = $request->optionsRadios;
                    $data->review = $request->review;
                }else{
                    $data = new OrderReview(array(
                        'order_id' => $request->order_id,
                        'teacher_id' => $request->teacher_id,
                        'rating' => $request->optionsRadios,
                        'review' => $request->review,
                        'creator' => session('user_id'),
                    ));
                }
                if($data->save()){
                    Log::setLog('ORRVC','Create Order Review : '.$order->order_id.' teacher : '.$order->get_teacher->teacher->name);
                    // $order->order_status = 2;
                    // $order->save();
                    return redirect()->route('home.index')->with('status','Successfully saved');
                }else{
                    return redirect()->back()->with('failed','Failed!');
                }
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
