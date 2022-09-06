<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\Handler;

use App\Modul;
use App\SubModul;

class SubModulController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $submodul = SubModul::all();

        return view('submodul.index',compact('submodul'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jenis = "create";
        $moduls = Modul::all();
        return view('submodul.form',compact('jenis','moduls'));
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
            'submodul_id' => 'required',
            'submodul_desc' => 'required',
            'modul_id' => 'required',
            'submodul_page' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $submodul = new SubModul(array(
                    // Informasi Pribadi
                    'submodul_id' => $request->submodul_id,
                    'submodul_desc' => $request->submodul_desc,
                    'submodul_page' => $request->submodul_page,
                    'modul_id' => $request->modul_id,
                ));

                $submodul->save();

                // success
                return redirect()->route('submodul.index');
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
        $sub = SubModul::where('submodul_id',$id)->first();
        $moduls = Modul::all();
        $jenis = "edit";
        return view('submodul.form',compact('jenis','moduls','sub'));
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
            'modul_id' => 'required',
            'submodul_desc' => 'required',
            'submodul_page' => 'required',
            'submodul_id' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $submodul = SubModul::where('submodul_id',$id)->first();

                $submodul->modul_id = $request->modul_id;
                $submodul->submodul_desc = $request->submodul_desc;
                $submodul->submodul_page = $request->submodul_page;
                $submodul->submodul_id = $request->submodul_id;

                $submodul->save();

                // success
                return redirect()->route('submodul.index');
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
            $submodul = SubModul::where('submodul_id',$id)->first();

            $submodul->delete();

            return redirect()->back();
        }catch(\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
