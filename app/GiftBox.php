<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GiftBox extends Model
{
    protected $table ='giftbox';
    protected $fillable = [
        'invitation_id', 'account_type', 'account_name', 'account_number','creator'
    ];

    public static function getGiftBoxDetail($inv_id){
        $giftboxs = GiftBox::where('invitation_id', $inv_id)->get();

        $data = collect();
        $data->put('invitation_id', $inv_id);
        $detail = collect();

        foreach($giftboxs as $gift){
            $row = collect();
            $row->put('account_type', $gift->account_type);
            $row->put('account_name', $gift->account_name);
            $row->put('account_number', $gift->account_number);
            $detail->push($row);
        }
        $data->put('accounts',$detail);

        return $data;
    }

    public static function getAllGiftBox(){
        $giftboxs = GiftBox::groupBy('invitation_id')->get();
        $data = collect();

        foreach($giftboxs as $gift){
            $data->push(GiftBox::getGiftBoxDetail($gift->invitation_id));
        }

        return $data;
    }
}
