<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Banner;
use App\Link;
use App\MenuMapping;
use App\Log;
use App\Jadwal;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::orderBy('urutan', 'asc')->get();
        $page = MenuMapping::getMap(session('user_id'),"COBN");

        return view('banner.index', compact('banners', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $links = Link::all();
        return view('banner.form', compact('links'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required',
            'button_name' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $image = "noimage.jpg";
                $action = "#";
                $button_name= "#";

                if($request->button == "on"){
                    $button = 1;
                }else{
                    $button = 0;
                }

                if($request->action <> NULL){
                    $action = $request->action;
                }

                if($request->button_name <> NULL){
                    $button_name = $request->button_name;
                }

                // Upload Foto
                if($request->image <> NULL|| $request->image <> ''){
                    $image = $request->image->getClientOriginalName();
                    $request->image->move(public_path('assets/images/banner/'),$image);
                }

                if($request->urutan == 1){
                    if(Banner::where('urutan',1)->count() != 0){
                        return redirect()->back()->with('warning', 'Banner urutan 1 hanya diperbolehkan ada satu. Ubah banner lain yang berurutan "1" terlebih dahulu, untuk mengubah banner ini menjadi urutan 1');
                    }
                }

                $banner = new Banner(array(
                    // Informasi Pribadi
                    'title' => $request->title,
                    'description' => $request->description,
                    'button' => $button,
                    'button_name' => $button_name,
                    'action' => $action,
                    'urutan' => $request->urutan,
                    'image' => $image,
                    'creator' => session('user_id'),
                ));
                $banner->save();

                Log::setLog('COBNC','Create Banner: '.$request->title);

                return redirect()->route('banner.index')->with('status', 'Data berhasil dibuat');
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
    public function show($id, Request $request)
    {
        // Banner
        $banners = Banner::orderBy('urutan', 'asc')->get();

        // Jadwal
        $jadwals = Jadwal::orderBy('day', 'asc')->get();

        return view('banner.preview', compact('banners', 'jadwals'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $banner = Banner::where('id', $id)->first();
        $links = Link::all();
        return view('banner.form', compact('banner','links'));
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
        // Validate
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required',
            'button_name' => 'required',
            'urutan' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $image = "noimage.jpg";
                $action = "#";
                $button_name= "#";

                $banner = Banner::where('id', $id)->first();

                if($request->urutan == 1){
                    if(Banner::where('urutan',1)->count() != 0){
                        return redirect()->back()->with('warning', 'Banner urutan 1 hanya diperbolehkan ada satu. Ubah banner lain yang berurutan "1" terlebih dahulu, untuk mengubah banner ini menjadi urutan 1');
                    }
                }

                // Upload Foto
                if($request->image <> NULL|| $request->image <> ''){

                    if (file_exists(public_path('assets/images/banner/').$banner->image) && $banner->image != "noimage.jpg") {
                        unlink(public_path('assets/images/banner/').$banner->image);
                    }

                    $image = $request->image->getClientOriginalName();
                    $request->image->move(public_path('assets/images/banner/'),$image);
                }else{
                    $image = $banner->image;
                }

                if($request->button == "on"){
                    $button = 1;
                }else{
                    $button = 0;
                }

                if($request->action <> NULL){
                    $action = $request->action;
                }

                if($request->button_name <> NULL){
                    $button_name = $request->button_name;
                }

                $banner->title = $request->title;
                $banner->description = $request->description;
                $banner->button_name = $button_name;
                $banner->button = $button;
                $banner->action = $action;
                $banner->image = $image;
                $banner->urutan = $request->urutan;
                $banner->creator = session('user_id');
                $banner->save();

                Log::setLog('COBNU','Update Banner: '.$request->title);

                return redirect()->route('banner.index')->with('status', 'Data berhasil dibuat');
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
        $banner = Banner::where('id',$id)->first();
        $title = $banner->title;
        if($banner->image != "noimage.jpg"){
            unlink(public_path('assets/images/banner/').$banner->image);
        }

        $banner->delete();
        Log::setLog('COBND','Delete Banner: '.$title);
        return "true";
    }
}
