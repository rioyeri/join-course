<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complement extends Model
{
    protected $table ='complement';
    protected $fillable = [
        'invitation_id', 'icon', 'song', 'banner', 'creator'
    ];

    public function creator_name(){
        return $this->belongsTo('App\User','creator','id');
    }
}
