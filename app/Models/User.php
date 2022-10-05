<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class User extends Model
{
    protected $table ='users';
    protected $fillable = [
        'username', 'password','bck_pass','name','last_login','login_status','address','phone','idnumber','email','birthdate','birthplace','regis_date','profilephoto','google_id'
    ];

    protected $hidden = [
        'password', 'bck_pass',
    ];

    public function rolemapping(){
        return $this->belongsTo('App\Models\RoleMapping','username','username');
    }

    public static function getBirthday(){
        Carbon::setLocale('id');
        $getTime = date('m', strtotime(Carbon::now()));

        // $getTime = date('Y-m-d', strtotime("1994-10-18"));
        $data = array();

        $user = User::whereMonth('birthdate', $getTime)->get();
        if(!empty($user)){
            foreach($user as $e){
                $array = array(
                    'name'    => $e->name,
                    'birthdate' => $e->birthdate,
                );
                array_push($data, $array);
            }
        }

        return $data;
    }

    public static function getPhoto($id_user){
        $user = User::where('id', $id_user)->first();
        if($user->profilephoto != null){
            $file = 'dashboard/assets/images/users/photos/'.$user->profilephoto;

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

            $file = 'dashboard/assets/letters/'.$first.".jpg";
        }
        return $file;
    }

    public static function getUserListByRole($operator, $role_id){
        $ids = RoleMapping::getUserIdByRole($role_id);
        if($operator == "!="){
            $users = User::whereNotIn('id', $ids)->get();
        }else{
            $users = User::whereIn('id', $ids)->get();
        }

        return $users;
    }

    public static function getAge($birthdate){
        $since = date_create($birthdate);
        $last_day = date_create();

        $umur = date_diff($since, $last_day)->y;
        
        return $umur;
    }
}
