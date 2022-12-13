<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\ApiFormatter;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Order;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Package;

class StatisticsController extends Controller
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
            $page = "SRST";
            return view('dashboard.statsreport.statistic',compact('page'));
        }
    }

    public function bestTeacher(Request $request){
        if($request->sort == "all"){
            $data = Teacher::bestTeacherThisMonth();
        }else{
            $month = date('m');
            $data = Teacher::bestTeacherThisMonth($month);
        }
        // echo json_encode($datas);

        if($data != NULL){
            return ApiFormatter::createApi(Response::HTTP_OK, 'Success', $data);
        }else{
            return ApiFormatter::createApi(Response::HTTP_BAD_REQUEST, 'Failed');
        }
    }

    public function mostSubject(Request $request){
        if($request->sort == "all"){
            $data = Course::mostSubject();
        }else{
            $month = date('m');
            $data = Course::mostSubject($month);
        }

        if($data != NULL){
            return ApiFormatter::createApi(Response::HTTP_OK, 'Success', $data);
        }else{
            return ApiFormatter::createApi(Response::HTTP_BAD_REQUEST, 'Failed');
        }
    }

    public function GradeStats(Request $request){
        if($request->sort == "all"){
            $data = Grade::gradeStats();
        }else{
            $month = date('m');
            $data = Grade::gradeStats($month);
        }

        if($data != NULL){
            return ApiFormatter::createApi(Response::HTTP_OK, 'Success', $data);
        }else{
            return ApiFormatter::createApi(Response::HTTP_BAD_REQUEST, 'Failed');
        }
    }

    public function OrderTypeStats(Request $request){
        if($request->sort == "all"){
            $data = Order::orderTypeStats();
        }else{
            $month = date('m');
            $data = Order::orderTypeStats($month);
        }

        if($data != NULL){
            return ApiFormatter::createApi(Response::HTTP_OK, 'Success', $data);
        }else{
            return ApiFormatter::createApi(Response::HTTP_BAD_REQUEST, 'Failed');
        }
    }

    public function PackageStats(Request $request){
        if($request->sort == "all"){
            $data = Package::getPackageStats();
        }else{
            $month = date('m');
            $data = Package::getPackageStats($month);
        }

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
