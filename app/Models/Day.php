<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    protected $table ='day';
    protected $fillable = [
        'nama_hari','day_name'
    ];

    public static function getStartOfWeekDate($date=null, $day_id){
        if ($date instanceof \DateTime) {
            $date = clone $date;
        } else if (!$date) {
            $date = new \DateTime();
        } else {
            $date = new \DateTime($date);
        }
        
        $date->setTime(0, 0, 0);
        
        if ($date->format('N') == $day_id) {
            // If the date is already a Monday, return it as-is
            return $date;
        } else {
            // Otherwise, return the date of the nearest Monday in the past
            // This includes Sunday in the previous week instead of it being the start of a new week
            return $date->modify('next '.Day::getDay($day_id));
        }
    }

    public static function getDay($id){
        $days = array(
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
            7 => 'sunday'
        );

        return $days[$id];
    }
}
