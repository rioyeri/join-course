<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReview extends Model
{
    protected $table ='order_review';
    protected $fillable = [
        'order_id','teacher_id','rating','review','creator'
    ];

    public function get_order(){
        return $this->belongsTo('App\Models\Order','order_id','id');
    }

    public function get_teacher(){
        return $this->belongsTo('App\Models\Teacher', 'teacher_id', 'id');
    }

    public function get_creator(){
        return $this->belongsTo('App\Models\User', 'creator', 'id');
    }

    public static function getRating($teacher_id){
        $rating = 5.0;
        $total_rate = OrderReview::where('teacher_id', $teacher_id)->sum('rating');
        $total_review = OrderReview::where('teacher_id', $teacher_id)->count();
        if($total_review != 0){
            $rating = $total_rate/$total_review;
        }
        return number_format($rating, 1, ",", ".");
    }

    public static function getReviewCount($teacher_id){
        $total_review = OrderReview::where('teacher_id', $teacher_id)->count();
        if($total_review == 0){
            $total_review = 1;
        }
        return $total_review;
    }
}
