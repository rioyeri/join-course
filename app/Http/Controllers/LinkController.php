<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Link;
use App\MenuMapping;
use App\Log;
use App\Sosmed;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $links = Link::all();
        $page = MenuMapping::getMap(session('user_id'),"COLN");

        return view('link.index', compact('links', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sosmeds = Sosmed::all();
        return view('link.form', compact('sosmeds'));
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
            'link' => 'required',
            'category' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $link = new Link(array(
                    // Informasi Pribadi
                    'title' => $request->title,
                    'category' => $request->category,
                    'description' => $request->description,
                    'link' => $request->link,
                    'creator' => session('user_id'),
                ));
                $link->save();

                Log::setLog('COLNC','Create Link: '.$request->title);

                return redirect()->route('link.index')->with('status', 'Data berhasil dibuat');
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
        $link = Link::where('id', $id)->first();
        $sosmeds = Sosmed::all();
        return view('link.form', compact('link','sosmeds'));
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
            'link' => 'required',
            'category' => 'required'
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $link = Link::where('id', $id)->first();

                $link->title = $request->title;
                $link->description = $request->description;
                $link->category = $request->category;
                $link->link = $request->link;
                $link->creator = session('user_id');
                $link->save();

                Log::setLog('COLNU','Update Link: '.$request->title);

                return redirect()->route('link.index')->with('status', 'Data berhasil dibuat');
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
        $link = Link::where('id',$id)->first();
        $name = $link->name;
        $link->delete();
        Log::setLog('COLND','Delete Link: '.$name);
        return "true";
    }
}
