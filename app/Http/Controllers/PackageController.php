<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Package;
use App\Models\Log;
use App\Models\MenuMapping;
use App\Models\RecycleBin;

use Symfony\Component\HttpFoundation\Response;
use App\Helpers\ApiFormatter;
use App\Models\Grade;
use App\Models\PackageGrade;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $datas = Package::dataIndex($request);
            echo json_encode($datas);
        }else{
            $page = "MDPC";
            $submoduls = MenuMapping::getMap(session('role_id'),$page);
            return view('dashboard.masterdata.package.index',compact('page','submoduls'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $grades = Grade::all();
        return response()->json(view('dashboard.masterdata.package.form',compact('grades'))->render());
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
            'name' => 'required',
            'price' => 'required',
            'discount_rate' => 'required',
            'number_meet' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                // $discount_rate = floatval(str_replace(',', '.', str_replace('.', '', $request->discount_rate)));
                $discount_rate = floatval($request->discount_rate);

                $data = new Package(array(
                    "name" => $request->name,
                    "description" => $request->description,
                    "price" => $request->price,
                    "discount_rate" => $discount_rate,
                    "number_meet" => $request->number_meet,
                    "creator" => session('user_id'),
                ));
                if($data->save()){
                    if(isset($request->package_grade)){
                        foreach($request->package_grade as $key){
                            $detail = new PackageGrade(array(
                                "package_id" => $data->id,
                                "grade_id" => $key,
                            ));
                            $detail->save();
                        }
                    }
                }

                Log::setLog('MDPCC','Create Package : '.$request->name);
                return redirect()->route('package.index')->with('status','Successfully saved');
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
        $data = Package::where('id', $id)->first();

        if($data != NULL){
            return ApiFormatter::createApi(Response::HTTP_OK, 'Success', $data);
        }else{
            return ApiFormatter::createApi(Response::HTTP_BAD_REQUEST, 'Failed');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Package::where('id', $id)->first();
        $grades = Grade::all();
        $package_grade = array_values(array_column(DB::select("SELECT grade_id FROM package_grade WHERE package_id LIKE $id"), 'grade_id'));

        return response()->json(view('dashboard.masterdata.package.form',compact('data','grades','package_grade'))->render());
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
        // echo "<pre>";
        // print_r($request->all());
        // die;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'discount_rate' => 'required',
            'number_meet' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                // $discount_rate = floatval(str_replace(',', '.', str_replace('.', '', $request->discount_rate)));
                $discount_rate = floatval($request->discount_rate);

                Package::where('id', $id)->update(array(
                    "name" => $request->name,
                    "description" => $request->description,
                    "price" => $request->price,
                    "discount_rate" => $discount_rate,
                    "number_meet" => $request->number_meet,
                    "creator" => session('user_id'),
                ));

                if(PackageGrade::where('package_id', $id)->count() != 0){
                    PackageGrade::where('package_id', $id)->delete();
                }

                if(count($request->package_grade) && isset($request->package_grade)){
                    foreach($request->package_grade as $key){
                        $detail = new PackageGrade(array(
                            "package_id" => $id,
                            "grade_id" => $key,
                        ));
                        $detail->save();
                    }    
                }
                Log::setLog('MDPCU','Update Package : '.$request->name);
                return redirect()->route('package.index')->with('status','Successfully saved');
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
            $data = Package::where('id', $id)->first();
            $log_id = Log::setLog('MDPCD','Delete Package : '.$data->name);
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
                $data = Package::where('id', $id)->first();
                if($data->status == 0){
                    $new_status = 1;
                    $text_log = "Activate Package : ".$data->name;
                }else{
                    $new_status = 0;
                    $text_log = "Deactivate Package : ".$data->name;
                }
                $data->status = $new_status;
                $data->creator = session('user_id');
                $data->save();
                Log::setLog('MDPCS', $text_log);
                return "true";
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }
}
