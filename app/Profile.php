<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table ='profile';
    protected $fillable = [
        'first_name', 'mid_name', 'last_name','address', 'phone', 'email', 'creator'
    ];

    public function creator_name(){
        return $this->belongsTo('App\User','creator','id');
    }
}
