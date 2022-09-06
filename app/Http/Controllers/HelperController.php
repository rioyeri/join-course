<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\DB;

use App\DataKota;

class HelperController extends Controller
{
    public function getDataKota(Request $request){
        $kota = DataKota::where('kode_pusdatin_prov',$request->prov)->select('kode_pusdatin_kota','kab_kota')->get();

        $html = '<option value="#" disabled selected>Pilih Kab/Kota</>';
        foreach ($kota as $key) {
            $html.='<option value="'.$key->kode_pusdatin_kota.'">'.$key->kab_kota.'</option>';
        }
        echo $html;
    }

    public function checkBeforeDelete(Request $request){
        $id = $request->id;
        $type = $request->type;
        $text = "";

        if($type == "deleteemployee"){
            $piutang = PiutangKaryawan::where('employee_id', $id)->get();
            $sales = Sales::where('creator', $id)->get();
            $delivery = DeliveryOrder::where('petugas', $id)->get();
            $purchase = Purchase::where('creator', $id)->get();
            $receive = ReceiveDet::where('creator', $id)->get();
            $jurnal = Jurnal::where('creator', $id)->get();
            $i = 1;
            $j = 1;
            $k = 1;
            $l = 1;
            $m = 1;
            $n = 1;

            foreach($piutang as $piu){
                $text .= "<b>- ".$piu->id_jurnal."</b>";

                if($i % 2 == 0){
                    $text .="<br>";
                }else{
                    $text .="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $i++;
            }

            if($piutang->count() != 0 AND $sales->count() != 0){
                $text .= "<br><br>";
            }

            foreach($sales as $so){
                $text .= "<b>- ".$so->jurnal_id."</b>";

                if($j % 2 == 0){
                    $text .="<br>";
                }else{
                    $text .="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $j++;
            }

            if($delivery->count() != 0){
                $text .= "<br><br>";
            }

            foreach($delivery as $do){
                $text .= "<b>- ".$do->jurnal_id."</b>";

                if($k % 2 == 0){
                    $text .="<br>";
                }else{
                    $text .="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $k++;
            }

            if($purchase->count() != 0){
                $text .= "<br><br>";
            }

            foreach($purchase as $po){
                $text .= "<b>- ".$po->jurnal_id."</b>";

                if($l % 2 == 0){
                    $text .="<br>";
                }else{
                    $text .="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $l++;
            }

            if($receive->count() != 0){
                $text .= "<br><br>";
            }

            foreach($receive as $ri){
                $text .= "<b>- ".$ri->id_jurnal."</b>";

                if($m % 2 == 0){
                    $text .="<br>";
                }else{
                    $text .="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $m++;
            }

            if($jurnal->count() != 0){
                $text .= "<br><br>";
            }

            foreach($jurnal as $jn){
                $text .= "<b>- ".$jn->id_jurnal."</b>";

                if($n % 2 == 0){
                    $text .="<br>";
                }else{
                    $text .="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $n++;
            }
        }

        $data = array(
            'title' => "Terdapat beberapa transaksi yang tersangkut di transaksi ini :",
            'text' => $text,
        );

        return response()->json($data);
    }
}
