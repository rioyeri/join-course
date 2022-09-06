<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\AboutUs;
use App\MenuMapping;
use App\Log;

class AboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $abouts = AboutUs::all();
        $page = MenuMapping::getMap(session('user_id'),"COAU");

        return view('about.index', compact('abouts', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('about.form');
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
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $image = "noimage.jpg";
                // Upload Foto
                if($request->image <> NULL|| $request->image <> ''){
                    $image = $request->image->getClientOriginalName();
                    $request->image->move(public_path('assets/images/aboutus/'),$image);
                }

                $about = new AboutUs(array(
                    // Informasi Pribadi
                    'title' => $request->title,
                    'description' => $request->description,
                    'image' => $image,
                    'creator' => session('user_id'),
                ));
                $about->save();

                Log::setLog('COAUC','Create About Us: '.$request->title);

                return redirect()->route('aboutus.index')->with('status', 'Data berhasil dibuat');
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
        // About US
        $aboutus = AboutUs::where('id', 3)->first()->description;
        $sejarah = AboutUs::where('id', 2)->first();
        $gembala = AboutUs::where('id', 1)->first();

        return view('about.preview', compact('aboutus', 'sejarah', 'gembala'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $about = AboutUs::where('id', $id)->first();

        return view('about.form', compact('about'));
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
        // Validate
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $about = AboutUs::where('id', $id)->first();

                // Upload Foto
                if($request->image <> NULL|| $request->image <> ''){

                    if (file_exists(public_path('assets/images/aboutus/').$about->image) && $about->image != "noimage.jpg") {
                        unlink(public_path('assets/images/aboutus/').$about->image);
                    }

                    $image = $request->image->getClientOriginalName();
                    $request->image->move(public_path('assets/images/aboutus/'),$image);
                }else{
                    $image = $about->image;
                }

                $about->title = $request->title;
                $about->description = $request->description;
                $about->image = $image;
                $about->creator = session('user_id');
                $about->save();

                Log::setLog('COAUU','Update About Us: '.$request->title);

                return redirect()->route('aboutus.index')->with('status', 'Data berhasil diupdate');
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
        $about = AboutUs::where('id',$id)->first();
        $name = $about->name;
        if($about->image != "noimage.jpg"){
            unlink(public_path('assets/images/aboutus/').$about->image);
        }

        $about->delete();
        Log::setLog('COAUD','Delete About Us: '.$name);
        return "true";
    }
}
