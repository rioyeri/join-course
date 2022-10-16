<?php

namespace App\Http\Controllers;

use App\Models\BootstrapIcon;
use App\Models\ContentHome;
use App\Models\ContentHomeDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Log;
use App\Models\MenuMapping;
use App\Models\RecycleBin;
use App\Models\Teacher;
use App\Models\User;

class ContentManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $datas = ContentHome::dataIndex($request);
            echo json_encode($datas);
        }else{
            $page = "CTHO";
            $submoduls = MenuMapping::getMap(session('role_id'),$page);
            return view('dashboard.content.content-management.index',compact('page','submoduls'));
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
        if ($request->ajax()) {
            try{
                $data = ContentHomeDetail::where('id', $request->detail_id)->first();
                
                if($request->content_id == 2){
                    $image = $request->image;
                }else{
                    // Upload Foto
                    if($request->image != null || $request->image != '' && $request->content_id != 3){
                        if($request->content_id == 4){
                            $path = "landingpage/assets/img/testimonials/";
                        }
                        if (file_exists(public_path($path).$data->image) && $data->image != null && isset($path)) {
                            unlink(public_path($path).$data->image);
                        }

                        $filename = str_replace( array( '\'', '"', ',' , ';', '<', '>',' ','!','?','.','/'), '', $data->title);

                        $image = $filename.'.'.$request->image->getClientOriginalExtension();
                        $request->image->move(public_path($path), $image);
                    }else{
                        $image = $data->image;
                    }
                }

                if($request->content_id == 3){
                    $user = User::where('name', 'LIKE', '%'.$request->title.'%')->first();
                    $teacher = Teacher::where('user_id', $user->id)->first();
                    $title = $request->title;
                    $subtitle = $teacher->title;
                    $description = $teacher->description;
                    $link = $request->link;
                    $link_text = $request->link_text;
                    $image = $user->profilephoto;
                }else{
                    $title = $request->title;
                    $subtitle = $request->subtitle;
                    $description = $request->description;
                    $link = $request->link;
                    $link_text = $request->link_text;
                }
                
    
                $data->title = $title;
                $data->subtitle = $subtitle;
                $data->description = $description;
                $data->link = $link;
                $data->link_text = $link_text;
                $data->image = $image;
                $data->save();
    
                return response()->json($data);
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
    public function show(Request $request, $id)
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
        $data = ContentHome::where('id', $id)->first();
        if($id != 1){
            if($id == 3){
                $teachers = Teacher::all();
                $details = ContentHomeDetail::where('content_id', $id)->get();
                $icons = BootstrapIcon::all();
                return response()->json(view('dashboard.content.content-management.form',compact('data', 'details', 'icons','teachers'))->render());    
            }else{
                $details = ContentHomeDetail::where('content_id', $id)->get();
                $icons = BootstrapIcon::all();
                return response()->json(view('dashboard.content.content-management.form',compact('data', 'details', 'icons'))->render());    
            }
        }else{
            return response()->json(view('dashboard.content.content-management.form',compact('data'))->render());
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
            'title' => 'required',
            'subtitle' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $data = ContentHome::where('id', $id)->first();
                $data->title = $request->title;
                $data->subtitle = $request->subtitle;
                $data->creator = session('user_id');
                $data->update();
                Log::setLog('CTHOU','Update Content Management : '.$data->segment);
                return redirect()->route('contentmanagement.index')->with('status','Successfully saved');
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
        //
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
                $data = ContentHome::where('id', $id)->first();
                if($data->status == 0){
                    $new_status = 1;
                    $text_log = "Activate Content : ".$data->segment;
                }else{
                    $new_status = 0;
                    $text_log = "Deactivate Content : ".$data->segment;
                }
                $data->status = $new_status;
                $data->creator = session('user_id');
                $data->save();
                Log::setLog('CTHOS', $text_log);
                return "true";
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }
}
