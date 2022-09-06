<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table ='banner';
    protected $fillable = [
        'title', 'description', 'button', 'button_name', 'action', 'urutan', 'creator', 'image'
    ];

    public function creator_name(){
        return $this->belongsTo('App\User','creator','id');
    }

    public function type(){
        if($this->button == 1){
            $button = "Yes";
        }else{
            $button = "No";
        }
        return $button;
    }

    public function get_link(){
        return $this->belongsTo('App\Link','action','id');
    }
}
