<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sosmed extends Model
{
    protected $table ='sosmed';
    protected $fillable = [
        'name', 'icon', 'creator'
    ];

    public function creator_name(){
        return $this->belongsTo('App\User','creator','id');
    }
}
