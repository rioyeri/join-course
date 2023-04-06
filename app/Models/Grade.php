<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table ='grade';
    protected $fillable = [
        'name','group'
    ];

    public static function gradeStats($sort){
        $data = collect();
        if($sort != 'all'){
            $start = date('Y-m-01'); // hard-coded '01' for first day
            $end  = date('Y-m-t');
        }
        $colors = Color::getColor()->shuffle();
        $i=0;

        foreach(Grade::orderBy('id', 'asc')->get() as $key){
            $temp = collect();
            $count_grade = Student::where('student_grade', $key->id)->count();

            $count_order = Order::whereIn('order_status', [1,2])->where('grade_id', $key->id);
            if($sort != 'all'){
                $count_order->whereDate('created_at', ">=", $start)->whereDate('created_at', "<=", $end);
            }
            $count_order = $count_order->count();

            $temp->put('grade_name', $key->name);
            $temp->put('grade_count', $count_grade);
            $temp->put('order_count', $count_order);
            $temp->put('color', $colors[$i]);
            $data->push($temp);
            if($i < 9){
                $i++;
            }else{
                $i=0;
            }
        }

        return $data;
    }
}
