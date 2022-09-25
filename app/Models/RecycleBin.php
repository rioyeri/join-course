<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecycleBin extends Model
{
    protected $table ='recyclebin';
    protected $fillable = [
        'log_id','table_origin','content', 'creator'
    ];

    public function creator(){
        return $this->belongsTo('App\Employee','creator','id');
    }

    public static function json_encode($content){
        $result = json_encode($content);
        return $result;
    }

    public static function moveToRecycleBin($log_id,$tablename,$content){
        $data = new RecycleBin(array(
            "log_id" => $log_id,
            "table_origin" => $tablename,
            "content" => $content,
            "creator" => session('user_id'),
        ));

        $data->save();
    }
}
