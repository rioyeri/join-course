<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentPromoDetail extends Model
{
    protected $table ='content_promo_detail';
    protected $fillable = [
        'promo_id','text','status','creator'
    ];

    public function get_status(){
        if($this->status == 1){
            $text = "Included";
        }else{
            $text = "Excluded";
        }

        return $text;
    }
}
