<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Invitation;
use App\GiftBox;
use App\MenuMapping;
use App\Log;

class GiftBoxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $giftboxs = json_decode(json_encode(GiftBox::getAllGiftBox()),FALSE);
        $page = MenuMapping::getMap(session('user_id'),"IVGB");
        return view('giftbox.index', compact('giftboxs','page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $page = MenuMapping::getMap(session('user_id'),"IVGB");
        if($request->ajax()){
            return response()->json(view('giftbox.detail.form',compact('page'))->render());
        }else{
            $giftboxs = GiftBox::select('invitation_id')->get();
            $invitation = Invitation::whereNotIn('Invitation_id', $giftboxs)->get();
            $page = MenuMapping::getMap(session('user_id'),"IVGB");
            return view('giftbox.form', compact('invitation', 'page'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // die;
        // Validate
        $validator = Validator::make($request->all(), [
            'invitation_id' => 'required|string',
            'account_type' => 'required|array',
            'account_name' => 'required|array',
            'account_number' => 'required|array',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                for($i=0; $i<count($request->account_number); $i++){
                    if(GiftBox::where('account_number', $request->account_number[$i])->count() == 0){
                        $giftbox = new GiftBox(array(
                            'invitation_id' => $request->invitation_id,
                            'account_type' => $request->account_type[$i],
                            'account_name' => $request->account_name[$i],
                            'account_number' => $request->account_number[$i],
                        ));
                        $giftbox->save();
                        Log::setLog('IVGBC','Create Gift Box: '.$request->invitation_id.' ('.$request->account_number[$i].')');
                    }
                }
                return redirect()->route('giftbox.index')->with('status', 'Data berhasil dibuat');
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
        if($request->ajax()){
            $page = MenuMapping::getMap(session('user_id'),"IVGB");
            $result = array();
            $giftbox = GiftBox::where('invitation_id', $id)->get();
            $count = 1;
            
            if($giftbox->count() != 0){
                foreach($giftbox as $detail){
                    $append = '<tr style="width:100%" id="trow_giftbox_detail'.$count.'" class="trow_giftbox_detail">
                    <td>'.$count.'</td>
                    <td>'.$detail->account_type.'</td>
                    <input type="hidden" name="account_type[]" id="account_type'.$count.'" value="'.$detail->account_type.'">
                    <td>'.$detail->account_number.'</td>
                    <input type="hidden" name="account_number[]" id="account_number'.$count.'" value="'.$detail->account_number.'">
                    <td>'.$detail->account_name.'</td>
                    <input type="hidden" name="account_name[]" id="account_name'.$count.'" value="'.$detail->account_name.'">
                    <td>';
                    
                    if (array_search("IVGBU",$page)){
                        $append .= '<a href="javascript:;" class="btn btn-info btn-rounded waves-effect w-md waves-info m-b-5" onclick="detailGiftBox(\'edit\','.$count.')">Update</a>';
                    }
                    if (array_search("IVGBD",$page)){
                        $append .= '<a href="javascript:;" class="btn btn-danger btn-rounded waves-effect w-md waves-danger m-b-5" onclick="detailGiftBox(\'delete\','.$count.')">Delete</a>';
                    }

                    $append .= '</td></tr>';
             
                    $data = array(
                        'append' => $append,
                        'count' => $count,
                        'value' => $detail->account_number,
                    );
                    array_push($result, $data);
                    $count++;
                }
            }
            return response()->json($result);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $page = MenuMapping::getMap(session('user_id'),"IVGB");
        if($request->ajax()){
            return response()->json(view('giftbox.detail.form',compact('page'))->render());
        }else{
            $giftbox = GiftBox::where('invitation_id',$id)->first();
            $invitation = Invitation::all();

            return view('giftbox.form', compact('giftbox', 'invitation', 'page'));
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        try{
            GiftBox::where('invitation_id',$id)->delete();
            Log::setLog('IVGBD','Delete Gift Box: '.$id);
            return "true";
        // fail
        }catch(\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function addToTable(Request $request){
        $count = $request->count+1;
        $page = MenuMapping::getMap(session('user_id'),"IVGB");
        
        if($request->account_name != ""){
            $append = '<tr style="width:100%" id="trow_giftbox_detail'.$count.'" class="trow_giftbox_detail">
            <td>'.$count.'</td>
            <td>'.$request->account_type.'</td>
            <input type="hidden" name="account_type[]" id="account_type'.$count.'" value="'.$request->account_type.'">
            <td>'.$request->account_number.'</td>
            <input type="hidden" name="account_number[]" id="account_number'.$count.'" value="'.$request->account_number.'">
            <td>'.$request->account_name.'</td>
            <input type="hidden" name="account_name[]" id="account_name'.$count.'" value="'.$request->account_name.'">
            <td>';
            
            if (array_search("IVGBU",$page)){
                $append .= '<a href="javascript:;" class="btn btn-info btn-rounded waves-effect w-md waves-info m-b-5" onclick="detailGiftBox(\'edit\','.$count.')">Update</a>';
            }
            if (array_search("IVGBD",$page)){
                $append .= '<a href="javascript:;" class="btn btn-danger btn-rounded waves-effect w-md waves-danger m-b-5" onclick="detailGiftBox(\'delete\','.$count.')">Delete</a>';
            }

            $append .= '</td></tr>';
        
            $data = array(
                'append' => $append,
                'count' => $count,
                'value' => $request->account_number,
            );
            $count++;
        }
        return response()->json($data);
    }
}
