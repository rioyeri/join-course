<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
        // if($total_review == 0){
        //     $total_review = 1;
        // }
        return $total_review;
    }

    public static function ReviewWithoutName(Request $request, $id){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $reviews = OrderReview::select('id', 'review')->where('teacher_id', $id);

        $totalRecords = $reviews->count();

        if($searchValue != ''){
            $reviews->where('review', 'LIKE', '%'.$searchValue.'%');
        }

        $totalRecordwithFilter = $reviews->count();

        if($columnName == "no"){
            $reviews->orderBy('id', $columnSortOrder);
        }else{
            $reviews->orderBy($columnName, $columnSortOrder);
        }

        $reviews = $reviews->offset($row)->limit($rowperpage)->get();

        $datas = collect();
        $i = $row+1;

        foreach ($reviews as $review) {
            $detail = collect();
            $detail->put('no', $i++);
            $detail->put('id',$review->id);
            $detail->put('review', $review->review);
            $datas->push($detail);
        }

        if(count($datas) == 0){
            $message = "Failed";
            $code = Response::HTTP_BAD_REQUEST;
        }else{
            $message = "Success";
            $code = Response::HTTP_OK;
        }

        $response = array(
            'message' => $message,
            'code' => $code,
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecordwithFilter,
            'data' => $datas,
        );

        return json_encode($response);
    }
}
