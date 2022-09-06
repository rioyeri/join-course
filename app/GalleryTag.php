<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GalleryTag extends Model
{
    protected $table ='gallery_tags';
    protected $fillable = [
        'tag_name', 'gallery_id', 'creator'
    ];

    public function creator_name(){
        return $this->belongsTo('App\User','creator','id');
    }
}
