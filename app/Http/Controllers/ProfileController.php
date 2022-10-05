<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\MenuMapping;
use App\Log;
use App\Profile;
use App\Link;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiles = Profile::all();
        $page = MenuMapping::getMap(session('user_id'),"COPR");

        return view('profile.index', compact('profiles', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('profile.form');
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
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $profile = new Profile(array(
                    // Informasi Pribadi
                    'first_name' => $request->first_name,
                    'mid_name' => $request->mid_name,
                    'last_name' => $request->last_name,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'creator' => session('user_id'),
                ));
                $profile->save();

                Log::setLog('COPRC','Create About Us: '.$request->name);

                return redirect()->route('profile.index')->with('status', 'Data berhasil dibuat');
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
        // footer
        $profil = Profile::where('id', 1)->first();

        // Link
        $links = Link::all();

        return view('profile.preview', compact('profil','links'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $profile = Profile::where('id', $id)->first();

        return view('profile.form', compact('profile'));
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
            'first_name' => 'required',
            'last_name' => 'required'
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $profile = Profile::where('id', $id)->first();

                $profile->first_name = $request->first_name;
                $profile->mid_name = $request->mid_name;
                $profile->last_name = $request->last_name;
                $profile->address = $request->address;
                $profile->phone = $request->phone;
                $profile->email = $request->email;
                $profile->creator = session('user_id');
                $profile->save();

                Log::setLog('COPRU','Update Profile');

                return redirect()->route('profile.index')->with('status', 'Data berhasil diupdate');
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
        $profile = Profile::where('id',$id)->first();
        $name = $profile->name;
        $profile->delete();
        Log::setLog('COPRD','Delete Profile: '.$name);
        return "true";
    }
}
