<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table ='order_detail';
    protected $fillable = [
        'order_id','schedule_time','link_zoom','link_drive','creator'
    ];

}
