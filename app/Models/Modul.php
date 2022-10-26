<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    protected $table ='modul';
    protected $fillable = [
        'modul_id', 'modul_desc','modul_icon','urutan'
    ];

    public static function getAllModul($type=null){
        if($type=="sidebar"){
            return Modul::where('modul_id', '!=', "DS")->orderBy('urutan','asc')->get();
        }else{
            return Modul::orderBy('urutan','asc')->get();
        }
    }
}
