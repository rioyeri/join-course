<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RoleMapping extends Model
{
    protected $table ='users_role';
    protected $fillable = [
        'username', 'role_id'
    ];

    public function role(){
        return $this->belongsTo('App\Models\Role');
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'username', 'username');
    }

    public function nonAdmin(){
        $d = array_values(array_column(DB::select("SELECT u.id FROM users as u
        INNER JOIN users_role as ur on u.username = ur.username
        INNER JOIN role as r ON r.id = ur.role_id
        WHERE r.role_name REGEXP 'Superadmin|Owner|Admin'"),'id'));

        return $d;
    }
}