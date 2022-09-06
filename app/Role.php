<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table ='role';
    protected $fillable = [
        'role_name', 'company_id','creator'
    ];
}
