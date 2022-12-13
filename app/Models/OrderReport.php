<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReport extends Model
{
    protected $table ='order_report';
    protected $fillable = [
        'order_id','title','file','link','creator'
    ];

    public function get_order(){
        return $this->belongsTo('App\Models\Order','order_id','id');
    }
}
