<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventDetail extends Model
{
    protected $table ='event_detail';
    protected $fillable = [
        'event_id','event_name', 'event_date', 'event_time_start', 'event_time_end', 'event_time_zone', 'event_location', 'event_location_address', 'event_location_url', 'event_streaming_channel', 'event_streaming_link','creator'
    ];
}
