<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageDetail extends Model
{
    protected $table ='package_detail';
    protected $fillable = [
        'package_id','text','status','creator'
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
