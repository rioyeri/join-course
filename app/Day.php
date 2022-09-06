<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    protected $table ='hari';
    protected $fillable = [
        'day_name', 'creator'
    ];

    public function creator_name(){
        return $this->belongsTo('App\User','creator','id');
    }

    public static function namaHari($day){
        $namahari = Day::where('id', $day)->first()->day_name;
        return $namahari;
    }

    public static function namaBulan($month){
        $daftar_bulan = array(
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        );


        return $daftar_bulan[$month];
    }

    public static function formatTanggalIndonesia($date){
        $hari_upacara = Day::namaHari(date("w", strtotime($date))+1);
        $tgl_upacara = date("d", strtotime($date));
        $bln_upacara = Day::namaBulan(date("m", strtotime($date)));
        $thn_upacara = date("Y", strtotime($date));
        $tanggal_upacara = $hari_upacara.", ".$tgl_upacara." ".$bln_upacara." ".$thn_upacara;

        return $tanggal_upacara;
    }

    public static function formatWaktuMulaiSampaiSelesai($start,$end,$timezone){
        if($end == NULL){
            $waktu = $start." ".$timezone." - Selesai";
        }else{
            $waktu = $start." - ".$end." ".$timezone;
        }
        return $waktu;
    }
}
