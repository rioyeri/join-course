<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table ='log';
    protected $fillable = [
        'sub_id', 'keterangan','creator'
    ];

    public static function setLog($sub,$desc,$user_id=null){
        if($user_id != null){
            $user = $user_id;
        }else{
            $user = session('user_id');
        }

        $log = new Log(array(
            'sub_id' => $sub,
            'keterangan' => $desc,
            'creator' => $user,
        ));

        $log->save();

        return $log->id;
    }
}
