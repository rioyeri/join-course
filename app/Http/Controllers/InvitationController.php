<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Messages;
use App\Invitation;
use App\Event;
use App\Gallery;
use App\GiftBox;
use App\Complement;
use App\Quote;
use App\MenuMapping;
use App\Log;

class InvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invitations = Invitation::all();
        $page = MenuMapping::getMap(session('user_id'),"IVPR");
        return view('invitation.index', compact('invitations','page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('invitation.form');
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
            'invitation_id' => 'required|string',
            'groom_name' => 'required|string',
            'groom_nickname' => 'required|string',
            'groom_father' => 'required|string',
            'groom_mother' => 'required|string',
            'bride_name' => 'required|string',
            'bride_nickname' => 'required|string',
            'bride_father' => 'required|string',
            'bride_mother' => 'required|string',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                if(Invitation::where('invitation_id',$request->invitation_id)->count() != 0){
                    return redirect()->back()->with('warning', 'Invitation ID sudah pernah terdaftar');
                }else{
                    $invitation = new Invitation(array(
                        'invitation_id' => $request->invitation_id,
                        'groom_name' => $request->groom_name,
                        'groom_nickname' => $request->groom_nickname,
                        'groom_father' => $request->groom_father,
                        'groom_mother' => $request->groom_mother,
                        'bride_name' => $request->bride_name,
                        'bride_nickname' => $request->bride_nickname,
                        'bride_father' => $request->bride_father,
                        'bride_mother' => $request->bride_mother,
                        'creator' => session('user_id'),
                    ));
                    $invitation->save();

                    Log::setLog('IVPR','Create Invitation: '.$request->invitation_id);

                    return redirect()->route('invitation.index')->with('status', 'Data berhasil dibuat');
                }
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
        $invitation = Invitation::where('id',$id)->first();
        return view('invitation.form', compact('invitation'));
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
            'invitation_id' => 'required|string',
            'groom_name' => 'required|string',
            'groom_nickname' => 'required|string',
            'groom_father' => 'required|string',
            'groom_mother' => 'required|string',
            'bride_name' => 'required|string',
            'bride_nickname' => 'required|string',
            'bride_father' => 'required|string',
            'bride_mother' => 'required|string',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $invitation = Invitation::where('id', $id)->first();
                // Upload Foto Groom
                if($request->groom_photo <> NULL|| $request->groom_photo <> ''){
                    if (file_exists(public_path('multimedia/'.$invitation->invitation_id.'/').$invitation->groom_photo) && $invitation->groom_photo != null) {
                        unlink(public_path('multimedia/'.$invitation->invitation_id.'/').$invitation->groom_photo);
                    }
                    $groom_photo = $request->groom_photo->getClientOriginalName();
                    $request->groom_photo->move(public_path('multimedia/'.$request->invitation_id.'/'),$groom_photo);
                }else{
                    $groom_photo = $invitation->groom_photo;
                }

                // Upload Foto Groom
                if($request->bride_photo <> NULL|| $request->bride_photo <> ''){
                    if (file_exists(public_path('multimedia/'.$invitation->invitation_id.'/').$invitation->bride_photo) && $invitation->bride_photo != null) {
                        unlink(public_path('multimedia/'.$invitation->invitation_id.'/').$invitation->bride_photo);
                    }

                    $bride_photo = $request->bride_photo->getClientOriginalName();
                    $request->bride_photo->move(public_path('multimedia/'.$request->invitation_id.'/'),$bride_photo);
                }else{
                    $bride_photo = $invitation->bride_photo;
                }

                Invitation::where('id', $id)->update(array(
                    'invitation_id' => $request->invitation_id,
                    'groom_name' => $request->groom_name,
                    'groom_nickname' => $request->groom_nickname,
                    'groom_father' => $request->groom_father,
                    'groom_mother' => $request->groom_mother,
                    'groom_photo' => $groom_photo,
                    'bride_name' => $request->bride_name,
                    'bride_nickname' => $request->bride_nickname,
                    'bride_father' => $request->bride_father,
                    'bride_mother' => $request->bride_mother,
                    'bride_photo' => $bride_photo,
                    'creator' => session('user_id'),
                ));

                Log::setLog('IVPR','Update Invitation: '.$request->invitation_id);

                return redirect()->route('invitation.index')->with('status', 'Data berhasil diubah');
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
            $invitation = Invitation::where('id',$id)->first();
            Log::setLog('IVPRD','Delete Invitation : '.$id);
            $invitation->delete();
            return "true";
        // fail
        }catch(\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function getInvitation($invitation_id, $receiver){
        $isExist = Invitation::on('mysql_invitation')->where('invitation_id', $invitation_id)->count();
        if($isExist != 0){
            // Get Receiver Name and come from
            $string = str_replace("+"," ",$receiver);
            $nama = substr($string, 0, strpos($string, "_"));
            $posisi = substr($string, strpos($string, "_") + 1);

            // get BrideGroom Data
            $invitation = Invitation::where('invitation_id', $invitation_id)->first();

            // get Event Details
            $events = json_decode(json_encode(Event::getEventDetail($invitation_id)),FALSE);
            
            // Message
            $messages = Messages::where('invitation_id', $invitation_id)->get();

            // Gift Box
            $giftbox = GiftBox::where('invitation_id', $invitation_id)->get();

            // Galleries
            $galleries = Gallery::where('invitation_id', $invitation_id)->get();

            // Complement
            $complement = Complement::where('invitation_id', $invitation_id)->first();

            // Quote
            $quote = Quote::where('invitation_id', $invitation_id)->first();
    
            return view('wedding-inv.main', compact('nama', 'posisi', 'invitation','events','messages','giftbox','galleries','complement','quote'));
        }else{
            return view('welcome.maintenance');
        }
    }

    public function sendMessage(Request $request, $invitation_id){
        if($request->name == "" OR $request->message == ""){
            echo false;
        }else{
            $message = new Messages(array(
                "invitation_id" => $invitation_id,
                "sender_name" => $request->name,
                "sender_message" => $request->message,
            ));
            // $message->setConnection('mysql_invitation');
            if($message->save()){
                echo true;
            }else{
                echo false;
            }
        }
    }
}
