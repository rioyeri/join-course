<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForgotPassword extends Model
{
    protected $table ='user_forgotpassword';
    protected $fillable = [
        'email','token'
    ];
}
