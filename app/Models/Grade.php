<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table ='grade';
    protected $fillable = [
        'name','group'
    ];

    public static function gradeStats($month=null){
        $data = collect();
        $periodic = "";
        if($month != null){
            $start = date('Y-'.$month.'-01'); // hard-coded '01' for first day
            $end  = date('Y-'.$month.'-t');
            $periodic = date('M Y');    
        }
        $colors = Color::getColor()->shuffle();
        $i=0;

        foreach(Grade::orderBy('id', 'asc')->get() as $key){
            $temp = collect();
            $count_grade = Student::where('student_grade', $key->id)->count();
            if($month != null){
                $count_order = Order::whereIn('order_status', [1,2])->whereDate('created_at', ">=", $start)->whereDate('created_at', "<=", $end)->where('grade_id', $key->id)->count();
            }else{
                $count_order = Order::whereIn('order_status', [1,2])->where('grade_id', $key->id)->count();
            }
            $temp->put('grade_name', $key->name);
            $temp->put('grade_count', $count_grade);
            $temp->put('order_count', $count_order);
            $temp->put('color', $colors[$i]);
            $temp->put('month_name', $periodic);
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
