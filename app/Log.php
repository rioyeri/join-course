<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table ='log';
    protected $fillable = [
        'sub_id', 'keterangan','creator'
    ];

    public static function setLog($sub,$desc){
        $log = new Log(array(
            'sub_id' => $sub,
            'keterangan' => $desc,
            'creator' => session('user_id'),
        ));

        $log->save();
    }
}
