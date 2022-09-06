<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubMapping extends Model
{
    protected $table ='modul_submapping';
    protected $fillable = [
        'id','submodul_id', 'jenis_id'
    ];
    public $incrementing = false;
}
