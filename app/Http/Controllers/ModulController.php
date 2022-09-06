<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\Handler;

use App\Modul;

class ModulController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $moduls = Modul::all();

        return view('modul.index',compact('moduls'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jenis = "create";
        return view('modul.form',compact('jenis'));
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
            'modul_id' => 'required',
            'modul_desc' => 'required',
            'modul_icon' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $modul = new Modul(array(
                    // Informasi Pribadi
                    'modul_id' => $request->modul_id,
                    'modul_desc' => $request->modul_desc,
                    'modul_icon' => $request->modul_icon,
                ));

                $modul->save();

                // success
                return redirect()->route('modul.index');
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
        $modul = Modul::where('modul_id',$id)->first();
        $jenis = "edit";
        return view('modul.form',compact('jenis','modul'));
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
            'modul_desc' => 'required',
            'modul_icon' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $modul = Modul::where('modul_id',$id)->first();

                $modul->modul_id = $request->modul_id;
                $modul->modul_desc = $request->modul_desc;
                $modul->modul_icon = $request->modul_icon;

                $modul->save();

                // success
                return redirect()->route('modul.index');
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
            $modul = Modul::where('modul_id',$id)->first();

            $modul->delete();

            return redirect()->back();
        }catch(\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
