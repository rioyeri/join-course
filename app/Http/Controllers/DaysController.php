<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Day;
use App\MenuMapping;
use App\Log;

class DaysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $days = Day::all();
        $page = MenuMapping::getMap(session('user_id'),"MDDY");

        return view('masterdata.days.index', compact('days', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('masterdata.days.form');
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
            'day_name' => 'required|string',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $day = new Day(array(
                    'day_name' => $request->day_name,
                    'creator' => session('user_id'),
                ));
                $day->save();

                Log::setLog('MDDYC','Create Day: '.$request->day_name);

                return redirect()->route('days.index')->with('status', 'Data berhasil dibuat');
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
        $day = Day::where('id', $id)->first();

        return view('masterdata.days.form', compact('day'));
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
            'day_name' => 'required|string',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $day = Day::where('id', $id)->first();

                $day->day_name = $request->day_name;
                $day->creator = session('user_id');
                $day->save();

                Log::setLog('MDDYU','Update Day: '.$request->day_name);

                return redirect()->route('days.index')->with('status', 'Data berhasil diupdate');
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
        $day = Day::where('id',$id)->first();
        $name = $day->day_name;
        $day->delete();
        Log::setLog('MDDYD','Delete Day: '.$name);
        return "true";
    }
}
