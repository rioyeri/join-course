<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Event;
use App\EventDetail;
use App\Invitation;
use App\MenuMapping;
use App\Log;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $events = Event::all();
        $events = json_decode(json_encode(Event::getAllEvents()),FALSE);
        $page = MenuMapping::getMap(session('user_id'),"IVEV");
        return view('event.index', compact('events','page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $page = MenuMapping::getMap(session('user_id'),"IVEV");
        if($request->ajax()){
            return response()->json(view('event.detail.form',compact('page'))->render());
        }else{
            $events = Event::select('invitation_id')->get();
            $invitation = Invitation::whereNotIn('Invitation_id', $events)->get();
            $page = MenuMapping::getMap(session('user_id'),"IVEV");
            return view('event.form', compact('invitation', 'page'));
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
        // Validate
        $validator = Validator::make($request->all(), [
            'invitation_id' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'event_name' => 'required|array',
            'event_date' => 'required|array',
            'event_time_start' => 'required|array',
            'event_location' => 'required|array',
            'event_location_address' => 'required|array',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                if(Event::where('invitation_id',$request->invitation_id)->count() != 0){
                    return redirect()->back()->with('warning', 'Invitation ID sudah pernah terdaftar');
                }else{
                    $event = new Event(array(
                        'invitation_id' => $request->invitation_id,
                        'title' => $request->title,
                        'description' => $request->description,
                        'creator' => session('user_id'),
                    ));
                    $event->save();

                    for($i=0; $i<count($request->event_name); $i++){
                        $event_detail = new EventDetail(array(
                            'event_id' => $event->id,
                            'event_name' => $request->event_name[$i],
                            'event_date' => $request->event_date[$i],
                            'event_time_start' => $request->event_time_start[$i],
                            'event_time_end' => $request->event_time_end[$i],
                            'event_time_zone' => $request->event_time_zone[$i],
                            'event_location' => $request->event_location[$i],
                            'event_location_address' => $request->event_location_address[$i],
                            'event_location_url' => $request->event_location_url[$i],
                            'event_streaming_channel' => $request->event_streaming_channel[$i],
                            'event_streaming_link' => $request->event_streaming_link[$i],
                        ));
                        $event_detail->save();
                    }

                    Log::setLog('IVEV','Create Event: '.$request->invitation_id);

                    return redirect()->route('event.index')->with('status', 'Data berhasil dibuat');
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
    public function show($id, Request $request)
    {
        if($request->ajax()){
            $page = MenuMapping::getMap(session('user_id'),"IVEV");
            $result = array();
            $event = Event::where('invitation_id', $id);
            $count = 1;
            
            if($event->count() != 0){
                $details = EventDetail::where('event_id', $event->first()->id)->get();
                foreach($details as $detail){
                    $append = '<tr style="width:100%" id="trow_event_detail'.$count.'" class="trow_event_detail">
                    <td>'.$count.'</td>
                    <td>'.$detail->event_name.'</td>
                    <input type="hidden" name="event_name[]" id="event_name'.$count.'" value="'.$detail->event_name.'">
                    <td>'.$detail->event_date.'</td>
                    <input type="hidden" name="event_date[]" id="event_date'.$count.'" value="'.$detail->event_date.'">
                    <td>'.$detail->event_time_start.'</td>
                    <input type="hidden" name="event_time_start[]" id="event_time_start'.$count.'" value="'.$detail->event_time_start.'">
                    <td>'.$detail->event_time_end.'</td>
                    <input type="hidden" name="event_time_end[]" id="event_time_end'.$count.'" value="'.$detail->event_time_end.'">
                    <td>'.$detail->event_time_zone.'</td>
                    <input type="hidden" name="event_time_zone[]" id="event_time_zone'.$count.'" value="'.$detail->event_time_zone.'">
                    <td>'.$detail->event_location.'</td>
                    <input type="hidden" name="event_location[]" id="event_location'.$count.'" value="'.$detail->event_location.'">
                    <td>'.$detail->event_location_address.'</td>
                    <input type="hidden" name="event_location_address[]" id="event_location_address'.$count.'" value="'.$detail->event_location_address.'">
                    <td>'.$detail->event_location_url.'</td>
                    <input type="hidden" name="event_location_url[]" id="event_location_url'.$count.'" value="'.$detail->event_location_url.'">
                    <td>'.$detail->event_streaming_channel.'</td>
                    <input type="hidden" name="event_streaming_channel[]" id="event_streaming_channel'.$count.'" value="'.$detail->event_streaming_channel.'">
                    <td>'.$detail->event_streaming_link.'</td>
                    <input type="hidden" name="event_streaming_link[]" id="event_streaming_link'.$count.'" value="'.$detail->event_streaming_link.'">
                    <td>';
                    
                    if (array_search("IVEVU",$page)){
                        $append .= '<a href="javascript:;" class="btn btn-info btn-rounded waves-effect w-md waves-info m-b-5" onclick="detailEvent(\'edit\','.$count.')">Update</a>';
                    }
                    if (array_search("IVEVD",$page)){
                        $append .= '<a href="javascript:;" class="btn btn-danger btn-rounded waves-effect w-md waves-danger m-b-5" onclick="detailEvent(\'delete\','.$count.')">Delete</a>';
                    }

                    $append .= '</td></tr>';
             
                    $data = array(
                        'append' => $append,
                        'count' => $count,
                        'value' => $detail->event_name,
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
        $page = MenuMapping::getMap(session('user_id'),"IVEV");
        if($request->ajax()){
            return response()->json(view('event.detail.form',compact('page'))->render());
        }else{
            $event = Event::where('id',$id)->first();
            $invitation = Invitation::all();

            return view('event.form', compact('event', 'invitation', 'page'));
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
        // echo "<pre>";
        // print_r($request->all());
        // die;
        // Validate
        $validator = Validator::make($request->all(), [
            'inv_id' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'event_name' => 'required|array',
            'event_date' => 'required|array',
            'event_time_start' => 'required|array',
            'event_location' => 'required|array',
            'event_location_address' => 'required|array',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                Event::where('id', $id)->update(array(
                    'title' => $request->title,
                    'description' => $request->description,
                    'creator' => session('user_id'),
                ));

                if(EventDetail::where('event_id', $id)->count() != 0){
                    EventDetail::where('event_id', $id)->delete();
                }

                for($i=0; $i<count($request->event_name); $i++){
                    $event_detail = new EventDetail(array(
                        'event_id' => $id,
                        'event_name' => $request->event_name[$i],
                        'event_date' => $request->event_date[$i],
                        'event_time_start' => $request->event_time_start[$i],
                        'event_time_end' => $request->event_time_end[$i],
                        'event_time_zone' => $request->event_time_zone[$i],
                        'event_location' => $request->event_location[$i],
                        'event_location_address' => $request->event_location_address[$i],
                        'event_location_url' => $request->event_location_url[$i],
                        'event_streaming_channel' => $request->event_streaming_channel[$i],
                        'event_streaming_link' => $request->event_streaming_link[$i],
                    ));
                    $event_detail->save();
                }

                Log::setLog('IVEV','Create Event: '.$request->inv_id);

                return redirect()->route('event.index')->with('status', 'Data berhasil dibuat');
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
            $event = Event::where('id',$id)->first();
            Log::setLog('IVEVD','Delete Event : '.$id);
            $event->delete();
            return "true";
        // fail
        }catch(\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function addToTable(Request $request){
        $count = $request->count+1;
        $page = MenuMapping::getMap(session('user_id'),"IVEV");
        
        if($request->name != ""){
            $append = '<tr style="width:100%" id="trow_event_detail'.$count.'" class="trow_event_detail">
            <td>'.$count.'</td>
            <td>'.$request->name.'</td>
            <input type="hidden" name="event_name[]" id="event_name'.$count.'" value="'.$request->name.'">
            <td>'.$request->date.'</td>
            <input type="hidden" name="event_date[]" id="event_date'.$count.'" value="'.$request->date.'">
            <td>'.$request->time_start.'</td>
            <input type="hidden" name="event_time_start[]" id="event_time_start'.$count.'" value="'.$request->time_start.'">
            <td>'.$request->time_end.'</td>
            <input type="hidden" name="event_time_end[]" id="event_time_end'.$count.'" value="'.$request->time_end.'">
            <td>'.$request->time_zone.'</td>
            <input type="hidden" name="event_time_zone[]" id="event_time_zone'.$count.'" value="'.$request->time_zone.'">
            <td>'.$request->location.'</td>
            <input type="hidden" name="event_location[]" id="event_location'.$count.'" value="'.$request->location.'">
            <td>'.$request->address.'</td>
            <input type="hidden" name="event_location_address[]" id="event_location_address'.$count.'" value="'.$request->address.'">
            <td>'.$request->location_url.'</td>
            <input type="hidden" name="event_location_url[]" id="event_location_url'.$count.'" value="'.$request->location_url.'">
            <td>'.$request->streaming_channel.'</td>
            <input type="hidden" name="event_streaming_channel[]" id="event_streaming_channel'.$count.'" value="'.$request->streaming_channel.'">
            <td>'.$request->streaming_link.'</td>
            <input type="hidden" name="event_streaming_link[]" id="event_streaming_link'.$count.'" value="'.$request->streaming_link.'">
            <td>';
            
            if (array_search("IVEVU",$page)){
                $append .= '<a href="javascript:;" class="btn btn-info btn-rounded waves-effect w-md waves-info m-b-5" onclick="detailEvent(\'edit\','.$count.')">Update</a>';
            }
            if (array_search("IVEVD",$page)){
                $append .= '<a href="javascript:;" class="btn btn-danger btn-rounded waves-effect w-md waves-danger m-b-5" onclick="detailEvent(\'delete\','.$count.')">Delete</a>';
            }

            $append .= '</td></tr>';
        
            $data = array(
                'append' => $append,
                'count' => $count,
                'value' => $request->name,
            );
            $count++;
        }
        return response()->json($data);
    }
}
