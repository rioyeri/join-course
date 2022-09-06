<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $table ='link';
    protected $fillable = [
        'title', 'description', 'link', 'creator','category'
    ];

    public function creator_name(){
        return $this->belongsTo('App\User','creator','id');
    }

    public function get_category(){
        return $this->belongsTo('App\Sosmed','category','name');
    }
}
