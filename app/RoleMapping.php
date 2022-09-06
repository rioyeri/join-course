<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RoleMapping extends Model
{
    protected $table ='users_role';
    protected $fillable = [
        'username','role_id'
    ];

    public function role(){
        return $this->belongsTo('App\Role');
    }


    public function nonStaff(){
        $d = array_values(array_column(DB::select("SELECT e.id FROM users as e
        INNER JOIN user_role as rl on e.username = rl.username
        INNER JOIN role as r ON r.id = rl.role_id
        WHERE r.role_name REGEXP 'Superadmin|Admin|Owner'"),'id'));

        return $d;
    }
}
