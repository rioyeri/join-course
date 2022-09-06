<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    protected $table ='aboutus';
    protected $fillable = [
        'title', 'description', 'image', 'creator'
    ];

    public function creator_name(){
        return $this->belongsTo('App\User','creator','id');
    }
}
