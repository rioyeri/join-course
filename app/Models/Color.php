<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    public static function getColor(){
        $color_booking_code = collect();
        // Blue Regatta
        $color_booking_code->push('#345999');
        // coral
        $color_booking_code->push('#FF7F50');
        // Cornflower Blue
        $color_booking_code->push('#6495ED');
        // Crimson
        $color_booking_code->push('#DC143C');
        // Orange
        $color_booking_code->push('#e59116');
        // Surfie Green
        $color_booking_code->push('#007575');
        // Dark Khaki
        $color_booking_code->push('#BDB76B');
        // light pink
        $color_booking_code->push('#FF96D5');
        // Dark Sea Green
        $color_booking_code->push('#8FBC8F');
        // Deep Sky Blue
        $color_booking_code->push('#00BFFF');

        return $color_booking_code;
    }
}
