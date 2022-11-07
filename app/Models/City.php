<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table ='city';
    protected $fillable = [
        'kota_id','kode_pusdatin_prov','provinsi','kode_pusdatin_kota','kab_kota'
    ];

    public static function getProvinsi(){
        return City::select('kode_pusdatin_prov','provinsi')->distinct()->get();
    }

    public static function getCity($city){
        return City::select('kode_pusdatin_kota','kab_kota')->where('kode_pusdatin_kota',$city)->first();
    }
}
