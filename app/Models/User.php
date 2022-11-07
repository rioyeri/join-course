<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Carbon\Carbon;

class User extends Model
{
    use SoftDeletes;
    protected $table ='users';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'username', 'password','bck_pass','name','last_login','login_status','address','phone','idnumber','email','birthdate','birthplace','regis_date','profilephoto','google_id','address_province','address_city'
    ];

    protected $hidden = [
        'password', 'bck_pass',
    ];

    public function rolemapping(){
        return $this->belongsTo('App\Models\RoleMapping','username','username');
    }

    public function get_teacher(){
        return $this->belongsTo('App\Models\Teacher','id','user_id');
    }

    public function get_student(){
        return $this->belongsTo('App\Models\Student','id','user_id');
    }

    public function location(){
        $location = "";
        if($this->address_city != ""){
            $location .= $this->address_city;
        }
        if($this->address_province != ""){
            $location .= ", ".$this->address_province;
        }

        return $location;
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
            $file = 'dashboard/assets/users/photos/'.$user->profilephoto;

            if(!file_exists($file)){
                $first = strtolower(substr($user->username, 0,1));

                if($first == "0"){
                    $first = "o";
                }

                $file = 'dashboard/assets/letters/'.$first.".jpg";
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

    public static function dataIndex(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $page = MenuMapping::getMap(session('role_id'),"USUS");
        $users = User::select('id', 'username', 'name','last_login','phone','email','birthdate','birthplace','regis_date','profilephoto','address_province','address_city','created_at', 'updated_at');

        $totalRecords = $users->count();

        if($searchValue != ''){
            $users->where(function ($query) use ($searchValue) {
                $query->orWhere('name', 'LIKE', '%'.$searchValue.'%')->orWhere('username', 'LIKE', '%'.$searchValue.'%')->orWhere('last_login', 'LIKE', '%'.$searchValue.'%')->orWhere('phone', 'LIKE', '%'.$searchValue.'%')->orWhere('email', '%'.$searchValue.'%')->orWhere('birthdate', '%'.$searchValue.'%')->orWhere('birthplace', '%'.$searchValue.'%')->orWhere('regis_date', '%'.$searchValue.'%')->orWhere('address_province', '%'.$searchValue.'%')->orWhere('address_city', '%'.$searchValue.'%');
            });
        }

        $totalRecordwithFilter = $users->count();

        if($columnName == "no"){
            $users->orderBy('updated_at', $columnSortOrder);
        }elseif($columnName == "location"){
            $users->orderBy('address_province', $columnSortOrder);
        }else{
            $users->orderBy($columnName, $columnSortOrder);
        }

        $users = $users->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($users as $key){
            $detail = collect();

            $location = "";
            if($key->address_city != ""){
                $location .= $key->address_city.", ";
            }

            if($key->address_province != ""){
                $location .= $key->address_province;
            }

            if($key->phone == ""){
                $phone = '<span class="text-danger">NOT SET YET</span>';
            }else{
                $phone_format = User::getFormatWANumber($key->phone);
                $phone_redirect = "https://wa.me/".$phone_format."?text=Hai ".$key->username.", Kami dari admin Flash Academia memberikan informasi bahwa ";
                $phone = $key->phone.' <a href="'.$phone_redirect.'" target="_blank"><i class="fa fa-whatsapp"></i></a>';
            }

            $options = '';
            if (array_search("USUSU",$page)){
                $options .= '<a class="btn btn-primary btn-round m-5" onclick="edit_data('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> Edit</a> ';
            }

            if (array_search("USUSD",$page)){
                $options .= '<a href="javascript:;" class="btn btn-danger btn-round m-5" onclick="delete_data('.$key->id.')"><i class="fa fa-trash-o"></i> Delete</a> ';
            }

            $detail->put('no', $i++);
            $detail->put('photo', $key->profilephoto);
            $detail->put('name', $key->name);
            $detail->put('username', $key->username);
            $detail->put('email', $key->email);
            $detail->put('phone',$phone);
            $detail->put('location', $location);
            $detail->put('birthdate',$key->birthdate);
            $detail->put('regis_date', $key->regis_date);
            $detail->put('last_login', $key->last_login);
            $detail->put('options', $options);
            $data->push($detail);
        }

        $response = array(
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecordwithFilter,
            'data' => $data,
        );

        return $response;
    }

    public static function getFormatWANumber($key_phone){
        if(preg_match('/\s/',$key_phone) != 0){
            $key_phone = str_replace(' ', '', $key_phone);
        }
        if(substr($key_phone,0,1) == "+"){
            $phone_format = substr($key_phone, 1);
        }elseif(substr($key_phone,0,1) == "0"){
            $phone_format = "62".substr($key_phone, 1);
        }

        return $phone_format;
    }
}
