<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Course;
use App\Models\Package;
use App\Models\Log;
use App\Models\MenuMapping;
use App\Models\OrderDetail;
use App\Models\OrderPayment;
use App\Models\OrderReport;
use App\Models\RecycleBin;

class DashboardController extends Controller
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
            }elseif($request->type == "notyet-confirm-order"){
                $datas = Order::dataNotYetConfirmDashboard($request);
                echo json_encode($datas);
            }elseif($request->type == "chart_bestteacher"){
                $datas = Teacher::bestTeacherThisMonth($request->sort);
                echo json_encode($datas);
            }elseif($request->type == "chart_mostsubject"){
                $datas = Course::mostSubject($request->sort);
                echo json_encode($datas);
            }elseif($request->type == "chart_grade"){
                $datas = Grade::gradeStats($request->sort);
                echo json_encode($datas);
            }elseif($request->type == "chart_ordertype"){
                $datas = Order::orderTypeStats($request->sort);
                echo json_encode($datas);
            }elseif($request->type == "chart_package"){
                $datas = Package::getPackageStats($request->sort);
                echo json_encode($datas);
            }elseif($request->type == "chart_orderreport"){
                $datas = Order::orderReport();
                echo json_encode($datas);
            }elseif($request->type == "chart_incomereport"){
                $datas = OrderPayment::incomeReport();
                echo json_encode($datas);
            }elseif($request->type == "incoming-payment"){
                $datas = Order::dataIncomingPayment($request);
                echo json_encode($datas);
            }
        }else{
            if(session('role_id') == 5){
                // Teacher
                $page = "DS";
                // $submoduls = MenuMapping::getMap(session('role_id'),$page);
                return view('dashboard.home.teacher.dashboard',compact('page'));
            }elseif(session('role_id') == 4){
                // Student
                $page = "DS";
                return view('dashboard.home.student.dashboard',compact('page'));
            }else{
                // Others
                $page = "DS";
                return view('dashboard.home.dashboard',compact('page'));
            }
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
        if($request->ajax()){
            $validator = Validator::make($request->all(), [
                'order_id' => 'required',
                'title' => 'required',
                'file' => 'required',
            ]);
            // IF Validation fail
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            // Validation success
            }else{
                try{
                    $submoduls = MenuMapping::getMap(session('role_id'),"DSRP");
                    $order = Order::where('id', $request->order_id)->first();

                    $data = new OrderReport(array(
                        'order_id' => $request->order_id,
                        'title' => $request->title,
                        'link' => $request->link,
                        'creator' => session('user_id'),
                    ));
                    $data->save();

                    $file = null;
                    if($request->file <> NULL|| $request->file <> ''){
                        $path = 'dashboard/assets/order/';
                        $new_path = $path.$order->order_id;
                        if(!file_exists($new_path)){
                            mkdir($new_path);
                        }
                        $file = $request->file->getClientOriginalName();
                        $request->file->move(public_path($new_path),$file);
                    }
                    $data->file = $file;
                    $data->save();
                    
                    Log::setLog('DSRPU','Update Course Report : '.$order->order_id);

                    // APPEND to table
                    $count = OrderReport::where('order_id', $request->order_id)->count();
                    $append = '<tr style="width:100%" id="trow'.$count.'" class="trow">
                    <td style="width:5%">'.$count.'</td>
                    <td style="width:25%">'.$data->created_at->format('Y-m-d H:i:s').'</td>
                    <td style="width:20%">'.$data->title.'</td>
                    <td style="width:20%"><a href="'.asset('dashboard/assets/order/'.$order->order_id.'/'.$data->file).'" target="_blank"><i class="fa fa-file-text-o"></i> '.$data->file.'</a></td>
                    <td style="width:20%"><a href="'.$data->link.'" target="_blank">'.$data->link.'</a></td>';
                    if(array_search("DSRPD", $submoduls)){
                        $append .= '<td style="width:10%" class="text-center"><a href="javascript:;" type="button" class="btn btn-danger btn-trans waves-effect w-md waves-danger m-b-5" onclick="deleteItem('.$data->id.','.$count.')">Delete</a></td>';
                    }
                    $append .= '</tr>';

                    $data = array(
                        'append' => $append,
                    );

                    return response()->json($data);
                    // return redirect()->route('home.index')->with('status','Successfully saved');
                }catch(\Exception $e){
                    return redirect()->back()->withErrors($e->getMessage());
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if($request->ajax()){
            $modal_type = $request->modal_type;
            $teacher = Teacher::where('id', $id)->first();
            if($modal_type == "review"){
                return response()->json(view('dashboard.home.teacher.review', compact('teacher'))->render());
            }elseif($modal_type == "order"){
                return response()->json(view('dashboard.home.teacher.history-order', compact('teacher'))->render());
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if($request->ajax()){
            $data = Order::where('id', $id)->first();
            if($request->type == "report"){
                $page = "DSRP";
                $submoduls = MenuMapping::getMap(session('role_id'),$page);
                $reports = OrderReport::where('order_id', $id)->get();
                return response()->json(view('dashboard.home.report-list',compact('data','reports','submoduls','page','submoduls'))->render());    
            }else{
                $page = "DSSC";
                $submoduls = MenuMapping::getMap(session('role_id'),$page);
                $exist_schedules = OrderDetail::where('order_id', $id)->get();
                
                $order = Order::where('id', $id)->first();
                $package_number_meet = $order->get_package->number_meet;
                $order_type = $order->order_type;

                return response()->json(view('dashboard.home.form',compact('data','exist_schedules','page','submoduls','package_number_meet','order_type'))->render());    
            }
        }
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
            '_token' => 'required',
            'schedule_datetime' => 'required|array',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $order = Order::where('id', $id)->first();
                // delete old detail if existed
                if(OrderDetail::where('order_id', $id)->count() != 0){
                    OrderDetail::where('order_id', $id)->delete();
                }

                for($i=0; $i<count($request->schedule_datetime); $i++){
                    $data = new OrderDetail(array(
                        'order_id' => $id,
                        'schedule_time' => $request->schedule_datetime[$i],
                        'link_zoom' => $request->link_zoom[$i],
                        'link_drive' => $request->link_drive[$i],
                        'creator' => session('user_id'),
                    ));
                    $data->save();
                }
                
                Log::setLog('DSSCU','Update Course Schedule : '.$order->order_id);
                return redirect()->route('home.index')->with('status','Successfully saved');
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
    public function destroy(Request $request, $id)
    {
        if($request->ajax()){
            if($request->type == "report"){
                $data = OrderReport::where('id', $id)->first();
                $path = 'dashboard/assets/order/'.$data->get_order->order_id.'/';
                $recycle_bin_path = 'dashboard/assets/recyclebin/';
                if (file_exists(public_path($path.$data->file)) && $data->file != null) {
                    rename(public_path($path.$data->file), public_path($recycle_bin_path.$data->file));
                }
                $log_id = Log::setLog('DSRPD','Delete Course Report : '.$data->title.' from '.$data->get_order->order_id);
                RecycleBin::moveToRecycleBin($log_id, $data->getTable(), json_encode($data));
                $data->delete();
                return "true";
            }
        }
    }
}
