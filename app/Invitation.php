<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $table ='invitation';
    protected $fillable = [
        'invitation_id', 'groom_name', 'groom_nickname', 'groom_father', 'groom_mother', 'groom_photo','bride_name', 'bride_nickname', 'bride_father', 'bride_mother', 'bride_photo'
    ];
}
