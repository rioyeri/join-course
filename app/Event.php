<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table ='event';
    protected $fillable = [
        'invitation_id', 'title', 'description','creator'
    ];

    public static function getEventDetail($inv_id){
        $event = Event::where('invitation_id', $inv_id)->first();

        $data = collect($event);
        $detail = collect();

        $details = EventDetail::where('event_id', $event->id)->get();
        foreach($details as $det){
            $row = collect($det);
            // Formating Date in English to Indonesian
            $tanggal = Day::formatTanggalIndonesia($det->event_date);
            $waktu = Day::formatWaktuMulaiSampaiSelesai($det->event_time_start, $det->event_time_end,$det->event_time_zone);

            $row->put('tanggal', $tanggal);
            $row->put('waktu', $waktu);
            $detail->push($row);
        }
        $data->put('event_detail',$detail);

        return $data;
    }

    public static function getAllEvents(){
        $events = Event::all();
        $data = collect();

        foreach($events as $event){
            $data->push(Event::getEventDetail($event->invitation_id));
        }

        return $data;
    }
}
