<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table ='jadwal';
    protected $fillable = [
        'name', 'day', 'day_note','start_time', 'end_time', 'notes'
    ];

    public function namahari(){
        return $this->belongsTo('App\Day','day','id');
    }
}
