<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\MenuMapping;
use App\Log;
use App\Sosmed;
use App\BoxIcon;

class SosmedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sosmeds = Sosmed::all();
        $page = MenuMapping::getMap(session('user_id'),"MDSM");

        return view('masterdata.sosmed.index', compact('sosmeds', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $icons = BoxIcon::all();

        return view('masterdata.sosmed.form', compact('icons'));
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
            'name' => 'required|string',
            'icon' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $sosmed = new Sosmed(array(
                    'name' => strtolower($request->name),
                    'icon' => $request->icon,
                    'creator' => session('user_id'),
                ));
                $sosmed->save();

                Log::setLog('MDSMC','Create Sosmed: '.strtolower($request->name));

                return redirect()->route('sosmed.index')->with('status', 'Data berhasil dibuat');
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
        $sosmed = Sosmed::where('id', $id)->first();
        $icons = BoxIcon::all();

        return view('masterdata.sosmed.form', compact('sosmed', 'icons'));
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
            'name' => 'required|string',
            'icon' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $sosmed = Sosmed::where('id', $id)->first();

                $sosmed->name = strtolower($request->name);
                $sosmed->icon = $request->icon;
                $sosmed->creator = session('user_id');
                $sosmed->save();

                Log::setLog('MDSMU','Update Sosmed: '.strtolower($request->name));

                return redirect()->route('sosmed.index')->with('status', 'Data berhasil diupdate');
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
        $sosmed = Sosmed::where('id',$id)->first();
        $name = $sosmed->name;
        $sosmed->delete();
        Log::setLog('MDSMD','Delete Sosmed: '.$name);
        return "true";
    }
}
