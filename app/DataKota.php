<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataKota extends Model
{
    protected $table ='datakota';
    protected $fillable = [
        'kota_id','kode_pusdatin_prov','provinsi','kode_pusdatin_kota','kab_kota'
    ];

    public static function getProvinsi(){
        return DataKota::select('kode_pusdatin_prov','provinsi')->distinct()->get();
    }

    public static function getCity($city){
        return DataKota::select('kode_pusdatin_kota','kab_kota')->where('kode_pusdatin_kota',$city)->first();
    }
}
