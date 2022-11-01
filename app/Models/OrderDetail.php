<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table ='order_detail';
    protected $fillable = [
        'order_id','schedule_time','link_zoom','link_drive','creator'
    ];

    public static function getWALink($detail_id, $type){
        $order_detail = OrderDetail::where('id', $detail_id)->first();
        $order = Order::where('id', $order_detail->order_id)->first();
        if($type == 'student'){
            $phone_format = User::getFormatWANumber($order->get_student->student->phone);
            $name = $order->get_student->student->name;
        }elseif($type == 'teacher'){
            $phone_format = User::getFormatWANumber($order->get_teacher->teacher->phone);
            $name = $order->get_student->student->name;
        }
        $phone_redirect = "https://api.whatsapp.com/send?phone=".$phone_format."&text=Hai%20".$name.",%20Kami%20dari%20admin%20Flash%20Academia%20memberikan%20informasi%20bahwa%20";
        $phone_redirect .= "pertemuan%20selanjutnya%20akan%20diadakan%20secara%20online%20melalui%20link%20berikut%20%3A%0A";
        $phone_redirect .= "%0A".$order_detail->link_zoom."%0A";
        $phone_redirect .= "Waktu%20%3A%20".$order_detail->schedule_time."%0A";
        if($order_detail->link_drive != ""){
            $phone_redirect .= "%0ABerikut%20File%20file%20pendukung%20yang%20bisa%20diunduh%20terlebih%20dahulu%20%3A%0A";
            $phone_redirect .= $order_detail->link_drive."%0A";
        }
        $phone_redirect .= "%0ATerima%20kasih%0aFlash%20Academia";

        return $phone_redirect;
    }
}
