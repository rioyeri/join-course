<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Quote;
use App\Invitation;
use App\Complement;
use App\MenuMapping;
use App\Log;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quotes = Quote::all();
        $page = MenuMapping::getMap(session('user_id'),"IVQT");

        return view('quote.index', compact('quotes', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = MenuMapping::getMap(session('user_id'),"IVQT");
        $quote = Quote::select('invitation_id')->get();
        $invitation = Invitation::whereNotIn('Invitation_id', $quote)->get();
        
        return view('quote.form',compact('page', 'invitation'));
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
            'text' => 'required|string',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $image = null;
                // Upload Foto
                if($request->image <> NULL|| $request->image <> ''){
                    $image = $request->image->getClientOriginalName();
                    $request->image->move(public_path('multimedia/'.$request->invitation_id.'/'),$image);
                }

                $quote = new Quote(array(
                    // Informasi Pribadi
                    'invitation_id' => $request->invitation_id,
                    'title' => $request->title,
                    'text' => $request->text,
                    'bg_image' => $image,
                    'creator' => session('user_id'),
                ));
                $quote->save();

                Log::setLog('IVQTC','Create Quote: '.$request->title.' ('.$request->invitation_id.')');

                return redirect()->route('quote.index')->with('status', 'Data berhasil dibuat');
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
        $invitation = Invitation::where('invitation_id',$id)->first();
        $quote = Quote::where('invitation_id', $invitation->invitation_id)->first();
        $complement = Complement::where('invitation_id', $id)->first();
    
        return view('quote.preview', compact('quote','invitation','complement'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quote = Quote::where('id', $id)->first();
        $invitation = Invitation::all();

        return view('quote.form', compact('quote','invitation'));
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
            'text' => 'required|string',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $quote = Quote::where('id', $id)->first();
                $image = null;
                // Upload Foto
                if($request->image <> NULL|| $request->image <> ''){

                    if (file_exists(public_path('multimedia/'.$request->inv_id.'/').$quote->bg_image) && $quote->bg_image != null) {
                        unlink(public_path('multimedia/'.$request->inv_id.'/').$quote->bg_image);
                    }

                    $image = $request->image->getClientOriginalName();
                    $request->image->move(public_path('multimedia/'.$request->inv_id.'/'),$image);
                }else{
                    $image = $quote->image;
                }

                $quote->title = $request->title;
                $quote->text = $request->text;
                $quote->bg_image = $image;
                $quote->creator = session('user_id');
                $quote->save();

                Log::setLog('IVQTU','Update Quote: '.$request->title.' ('.$request->invitation_id.')');

                return redirect()->route('quote.index')->with('status', 'Data berhasil dibuat');
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
        $quote = Quote::where('id',$id)->first();
        $title = $quote->title;
        $invitation_id = $quote->invitation_id;
        if(file_exists(public_path('multimedia/'.$invitation_id.'/').$quote->bg_image)){
            unlink(public_path('multimedia/'.$invitation_id.'/').$quote->bg_image);
        }
        $quote->delete();
        Log::setLog('IVQTD','Delete Image: '.$title.' ('.$invitation_id.')');
        return "true";
    }
}
