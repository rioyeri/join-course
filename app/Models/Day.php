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
        //     // If the date is already a Monday, return it as-is
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

    public static function find_closest($array, $date)
    {
        //$count = 0;
        foreach($array as $day)
        {
            //$interval[$count] = abs(strtotime($date) - strtotime($day));
            $interval[] = abs(strtotime($date) - strtotime($day));
            //$count++;
        }
    
        asort($interval);
        $closest = key($interval);
    
        echo $array[$closest];
    }

    public static function sortDays($schedules, $course_start){
        $day_start = date("N", strtotime($course_start));
        $interval = array();

        foreach($schedules as $key){
            // $interval[] = abs($day_start) - abs($key->day_id);
            $neg =  $key->day_id - $day_start;
            if($neg < 0){
                $value = abs($neg) + 7;
            }else{
                $value = $neg;
            }
            $array = array(
                // "interv" => abs($key->day_id) - abs($day_start),
                "interv" => $value,
                "day" => $key->day_id,
            );
            array_push($interval, $array);
        }

        asort($interval);

        $result = array();
        foreach($interval as $in){
            array_push($result, $in['day']);
        }
        // echo "<pre>";
        // print_r($interval);
        $sorted_day = implode(",", $result);

        return $sorted_day;
    }
}
