<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $table ='messages';
    protected $fillable = [
        'invitation_id', 'sender_name', 'sender_message'
    ];
}
