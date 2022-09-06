<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class User extends Model
{
    protected $table ='users';
    protected $fillable = [
        'username', 'password','bck_pass','name','last_login','login_status','address','phone','ktp','email','tmpt_lhr','tgl_lhr','regis_date','foto_profil'
    ];

    protected $hidden = [
        'password', 'bck_pass',
    ];


    public function rolemapping(){
        return $this->belongsTo('App\RoleMapping','username','username');
    }

    public static function getBirthday(){
        Carbon::setLocale('id');
        $getTime = date('m', strtotime(Carbon::now()));

        // $getTime = date('Y-m-d', strtotime("1994-10-18"));
        $data = array();

        $user = User::whereMonth('tgl_lhr', $getTime)->get();
        if(!empty($user)){
            foreach($user as $e){
                $array = array(
                    'name'    => $e->name,
                    'tanggal' => $e->tgl_lhr,
                );
                array_push($data, $array);
            }
        }

        return $data;
    }

    public static function getPhoto($id_user){
        $user = User::where('id', $id_user)->first();
        if($user->foto_profil != null){
            $file = 'assets/images/user/foto/'.$user->foto_profil;

            if(!file_exists($file)){
                $first = strtolower(substr($user->username, 0,1));

                if($first == "0"){
                    $first = "o";
                }

                $file = 'assets/images/letters/'.$first.".jpg";
            }
        }else{
            $first = strtolower(substr($user->username, 0,1));

            if($first == "0"){
                $first = "o";
            }

            $file = 'assets/images/letters/'.$first.".jpg";
        }
        return $file;
    }
}
