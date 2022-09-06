<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    protected $table ='modul';
    protected $fillable = [
        'modul_id', 'modul_desc','modul_icon','urutan'
    ];

    public static function getAllModul(){
        return Modul::orderBy('urutan','asc')->get();
    }
}
