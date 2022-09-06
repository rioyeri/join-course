<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Log;
use App\Jadwal;
use App\MenuMapping;
use App\Day;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jadwals = Jadwal::all();
        $page = MenuMapping::getMap(session('user_id'),"COJD");
        return view('jadwal.index', compact('jadwals','page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $days = Day::all();
        return view('jadwal.form',compact('days'));
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
            'nama_ibadah' => 'required|string',
            'jam_mulai' => 'required|string',
            'jam_selesai' => 'required|string',
            'hari' => 'required|string',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $jadwal = new Jadwal(array(
                    // Informasi Pribadi
                    'name' => $request->nama_ibadah,
                    'day' => $request->hari,
                    'day_note' => $request->day_note,
                    'start_time' => $request->jam_mulai,
                    'end_time' => $request->jam_selesai,
                    'notes' => $request->notes,
                ));
                $jadwal->save();

                Log::setLog('COJDC','Create Jadwal: '.$request->nama_ibadah);

                return redirect()->route('jadwal.index')->with('status', 'Data berhasil dibuat');
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
        // Jadwal
        $jadwals = Jadwal::orderBy('day', 'asc')->get();

        return view('jadwal.preview', compact('jadwals'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jadwal = Jadwal::where('id', $id)->first();
        $days = Day::all();
        return view('jadwal.form', compact('jadwal','days'));
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
            'nama_ibadah' => 'required|string',
            'jam_mulai' => 'required|string',
            'jam_selesai' => 'required|string',
            'hari' => 'required|string',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $jadwal = Jadwal::where('id', $id)->first();
                $jadwal->name = $request->nama_ibadah;
                $jadwal->day = $request->hari;
                $jadwal->day_note = $request->day_note;
                $jadwal->start_time = $request->jam_mulai;
                $jadwal->end_time = $request->jam_selesai;
                $jadwal->notes = $request->notes;
                $jadwal->save();

                Log::setLog('COJDU','Update Jadwal: '.$request->nama_ibadah);

                return redirect()->route('jadwal.index')->with('status', 'Data berhasil diupdate');
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
        $jadwal = Jadwal::where('id',$id)->first();
        $name = $jadwal->name;
        $jadwal->delete();
        Log::setLog('COJDD','Delete Jadwal: '.$name);
        return "true";
    }
}
