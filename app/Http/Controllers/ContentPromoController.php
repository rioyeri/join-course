<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\ContentPromo;
use App\Models\MenuMapping;
use App\Models\BootstrapIcon;
use App\Models\ContentPromoDetail;
use App\Models\Log;
use App\Models\Package;
use App\Models\PackageDetail;
use App\Models\RecycleBin;

class ContentPromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $datas = ContentPromo::dataIndex($request);
            echo json_encode($datas);
        }else{
            $page = "CTPR";
            $submoduls = MenuMapping::getMap(session('role_id'),$page);
            return view('dashboard.content.content-promo.index',compact('page','submoduls'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $icons = BootstrapIcon::all();
        $promo_package_id = ContentPromo::select('package_id')->get();
        $packages = Package::whereNotIn('id', $promo_package_id)->where('status', 1)->get();
        return response()->json(view('dashboard.content.content-promo.form', compact('icons','packages'))->render());
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
            // Detail
            try{
                $data = new ContentPromoDetail(array(
                    "promo_id" => $request->promo_id,
                    "text" => $request->feature,
                    "status" => $request->status,
                    "creator" => session('user_id'),
                ));
                $data->save();

                $i = ContentPromoDetail::where('promo_id', $request->promo_id)->count();

                $append = '<tr style="width:100%" id="trow'.$i.'" class="trow">
                <input type="hidden" id="detail_id'.$i.'" value="'.$data->id.'">
                <td>'.$i.'</td>
                <td>'.$data->get_status().'</td>
                <input type="hidden" name="status[]" id="status'.$i.'" value="'.$data->status.'">
                <td>'.$data->text.'</td>
                <input type="hidden" name="text[]" id="text'.$i.'" value="'.$data->text.'">
                <td class="text-center">
                <a href="javascript:;" type="button" class="btn btn-primary btn-sm" onclick="edit_row('.$i.')">Edit</a>
                <a href="javascript:;" type="button" class="btn btn-danger btn-sm" onclick="delete_row('.$i.')">Delete</a></td></tr>';

                $array = array(
                    "append" => $append,
                    "data" => $data,
                );
                return response()->json($array);
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }else{
            // Core
            $validator = Validator::make($request->all(), [
                'package_id' => 'required',
                'icon' => 'required',
            ]);
            // IF Validation fail
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            // Validation success
            }else{
                try{
                    $position = ContentPromo::orderBy('position', 'desc')->first()->position + 1;
                    $data = new ContentPromo(array(
                        "package_id" => $request->package_id,
                        'icon' => $request->icon,
                        'link_text' => $request->link_text,
                        'category' => $request->category,
                        "creator" => session('user_id'),
                        'position' => $position,
                    ));
                    $data->save();

                    for($i=0; $i < count($request->text); $i++){
                        $detail = new ContentPromoDetail(array(
                            "promo_id" => $data->id,
                            "text" => $request->text[$i],
                            "status" => $request->status[$i],
                            "creator" => session('user_id'),
                        ));
                        $detail->save();
                    }
                    Log::setLog('CTPRC','Create Promo : '.$data->get_package->name);
                    return redirect()->route('contentpromo.index')->with('status','Successfully saved');
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
        $data = ContentPromo::where('id', $id)->first();
        $icons = BootstrapIcon::all();
        $details = ContentPromoDetail::where('promo_id', $id)->get();
        $packages = Package::where('status', 1)->get();
        return response()->json(view('dashboard.content.content-promo.form',compact('data','icons','details','packages'))->render());
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
        if($request->ajax()){
            try{
                if(isset($request->package_name)){
                    for($i=0;$i<$request->rows; $i++){
                        $package = Package::where('name', 'LIKE', $request->package_name[$i])->first();
                        $promo = ContentPromo::where('package_id', $package->id)->first();
                        $promo->position = $i+1;
                        $promo->save();
                    }
                    return "true";
                }else{
                    $data = ContentPromoDetail::where('id', $id)->first();
                    $data->text = $request->feature;
                    $data->status = $request->status;
                    $data->creator = session('user_id');
                    $data->save();
    
                    Log::setLog('CTPRU','Update Detail Promo : '.$request->feature);
    
                    return response()->json($data);
                }
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }else{
            // Core
            $validator = Validator::make($request->all(), [
                'package_id' => 'required',
                'icon' => 'required',
            ]);
            // IF Validation fail
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            // Validation success
            }else{
                try{
                    $data = ContentPromo::where('id', $id)->first();
                    $data->package_id = $request->package_id;
                    $data->icon = $request->icon;
                    $data->link_text = $request->link_text;
                    $data->category = $request->category;
                    $data->creator = session('user_id');
                    $data->save();

                    Log::setLog('CTPRU','Update Promo : '.$data->get_package->name);
                    return redirect()->route('contentpromo.index')->with('status','Successfully saved');
                }catch(\Exception $e){
                    return redirect()->back()->withErrors($e->getMessage());
                }
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
        if(isset($request->row_number)){
            // Detail Promo
            $data = ContentPromoDetail::where('id', $id)->first();
            $log_id = Log::setLog('CTPRD','Delete Detail Promo : '.$data->text);
            RecycleBin::moveToRecycleBin($log_id, $data->getTable(), json_encode($data));
            $data->delete();
            return response()->json($request->row_number);
        }else{
            // Detail Promo
            $data = ContentPromo::where('id', $id)->first();
            $log_id = Log::setLog('CTPRD','Delete Promo : '.$data->get_package->name);
            RecycleBin::moveToRecycleBin($log_id, $data->getTable(), json_encode($data));
            $data->delete();
            return "true";
        }
    }
}
