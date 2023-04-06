<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Teacher extends Model
{
    use SoftDeletes;
    protected $table ='teacher';
    protected $fillable = [
        'user_id','title','description','availability','status'
    ];

    public function teacher(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function location(){
        $user = User::where('id', $this->user_id)->first();
        $location = "";
        if($user->address_city != ""){
            $location .= $user->address_city;
        }
        if($user->address_province != ""){
            $location .= ", ".$user->address_province;
        }

        return $location;
    }

    public function isItInstantOrder(){
        $result = false;
        $count_schedule = TeacherSchedule::where('teacher_id', $this->id)->count();
        if($count_schedule != 0){
            $result = true;
        }

        return $result;
    }

    public static function dataIndex(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $page = MenuMapping::getMap(session('role_id'),"MDTC");
        $teachers = Teacher::join('users as u', 'teacher.user_id', 'u.id')->select('teacher.id','u.name as teacher_name', 'teacher.user_id', 'title', 'description','status');

        $totalRecords = $teachers->count();

        if($searchValue != ''){
            $teachers->where(function ($query) use ($searchValue) {
                $user_id = User::select('id')->where(function ($query2) use ($searchValue){
                    $query2->orWhere('address_city', 'LIKE', '%'.$searchValue.'%')->orWhere('address_province', 'LIKE', '%'.$searchValue.'%');
                })->get();
                $query->orWhere('u.name', 'LIKE', '%'.$searchValue.'%')->orWhere('title', 'LIKE', '%'.$searchValue.'%')->orWhere('description', 'LIKE', '%'.$searchValue.'%')->orWhereIn('u.id', $user_id);
            });
        }

        $totalRecordwithFilter = $teachers->count();

        if($columnName == "no"){
            $teachers->orderBy('teacher.updated_at', $columnSortOrder);
        }else{
            $teachers->orderBy($columnName, $columnSortOrder);
        }

        $teachers = $teachers->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($teachers as $key){
            $detail = collect();
            $profile_button = '';
            $courses_button = '';
            // $prices_button = '';
            $schedules_button = '';
            $reviews_button = '';
 
            if (array_search("MDTCV",$page)){
                // if(TeacherPrice::where('teacher_id', $key->id)->count() != 0){
                //     $count_price = TeacherPrice::where('teacher_id', $key->id)->count();
                //     $color_price = "#000";
                // }else{
                //     $count_price = "Empty";
                //     $color_price = "#FF0000";
                // }

                if(TeacherSchedule::where('teacher_id', $key->id)->count() != 0){
                    $count_schedule = TeacherSchedule::where('teacher_id', $key->id)->count();
                    $color_schedule = "#000";
                }else{
                    $count_schedule = "Empty";
                    $color_schedule = "#FF0000";
                }

                if(TeacherCourse::where('teacher_id', $key->id)->count() != 0){
                    $count_course = TeacherCourse::where('teacher_id', $key->id)->count();
                    $color_course = "#000";
                }else{
                    $count_course = "Empty";
                    $color_course = "#FF0000";
                }

                if(OrderReview::where('teacher_id', $key->id)->count() != 0){
                    $count_review = OrderReview::where('teacher_id', $key->id)->count();
                    $color_review = "#000";
                }else{
                    $count_review = "Empty";
                    $color_review = "#FF0000";
                }

                $profile_button .= '<a class="btn btn-info m-5" onclick="view_profile('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-user"></i> View Profile</a> ';
                $courses_button .= '<a class="btn btn-info m-5" onclick="view_subject('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-list-ul"></i> View Subjects <span style="color:'.$color_course.'">('.$count_course.')</span></a> ';
                // $prices_button .= '<a class="btn btn-info m-5" onclick="view_price('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-usd"></i> View Packages <span style="color:'.$color_price.'">('.$count_price.')</span></a> ';
                $schedules_button .= '<a class="btn btn-info m-5" onclick="view_schedules('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-list-ul"></i> View Schedules <span style="color:'.$color_schedule.'">('.$count_schedule.')</span></a>';
                $reviews_button .= '<a class="btn btn-info m-5" onclick="view_reviews('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-list-ul"></i> View Reviews <span style="color:'.$color_review.'">('.$count_review.')</span></a>';
            }

            $options = '';
            if (array_search("MDTCD",$page)){
                $options .= '<a href="javascript:;" class="btn btn-danger btn-round m-5" onclick="delete_data('.$key->id.')"><i class="fa fa-trash-o"></i> Delete</a> ';
            }

            if (array_search("MDTCS",$page)){
                if($key->status == 0){
                    $options .= '<a class="btn btn-warning btn-round m-5" onclick="change_status('.$key->id.')"><i class="fa fa-power-off"></i> Non-Active</a> ';
                }else{
                    $options .= '<a class="btn btn-success btn-round m-5" onclick="change_status('.$key->id.')"><i class="fa fa-power-off"></i> Active</a> ';
                }
            }

            $detail->put('no', $i++);
            $detail->put('teacher_name', $key->teacher->name);
            $detail->put('title', $key->title);
            $detail->put('teacher_profile', $profile_button);
            $detail->put('courses', $courses_button);
            // $detail->put('prices',$prices_button);
            $detail->put('schedules', $schedules_button);
            $detail->put('reviews', $reviews_button);
            $detail->put('options', $options);
            $data->push($detail);
        }

        $response = array(
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecordwithFilter,
            'data' => $data,
        );

        return $response;
    }

    public static function getTeacherListByCourse($courses){
        $result = collect();
        $teacher_ids = TeacherCourse::whereIn('course_id',$courses)->select('teacher_id')->get();
        $teachers = Teacher::whereIn('id', $teacher_ids)->get();
        foreach($teachers as $teacher){
            $description = substr($teacher->description, 0, 50);
            if($teacher->teacher->profilephoto == null){
                $image = asset('dashboard/assets/noimage.jpg');
            }else{
                $image = asset('dashboard/assets/users/photos/'.$teacher->teacher->profilephoto);
            }
            $detail = collect();
            $detail->put('id', $teacher->id);
            $detail->put('title', $teacher->teacher->name);
            $detail->put('subtitle', $teacher->title);
            $detail->put('description', $description);
            $detail->put('image', $image);
            $result->push($detail);
        }
        return json_decode(json_encode($result), FALSE);
    }

    public static function getTeacherListByTeacherId($id){
        $result = collect();
        $teacher = Teacher::where('id', $id)->first();
        if($teacher->teacher->profilephoto == null){
            $image = asset('dashboard/assets/noimage.jpg');
        }else{
            $image = asset('dashboard/assets/users/photos/'.$teacher->teacher->profilephoto);
        }
        $location = "";
        if($teacher->teacher->address_city){
            $location .= $teacher->teacher->address_city.", ";
        }
        if($teacher->teacher->address_province){
            $location .= $teacher->teacher->address_province;
        }

        $rating = OrderReview::getRating($id);
        $review_count = OrderReview::getReviewCount($id);

        $result->put('name', $teacher->teacher->name);
        $result->put('title', $teacher->title);
        $result->put('description', $teacher->description);
        $result->put('location', $location);
        $result->put('image', $image);
        $result->put('rate', $rating);
        $result->put('review_count', $review_count);

        return json_decode(json_encode($result), FALSE);
        // return $result;
    }

    public static function getTeacherCourse($course_id){
        $teacher_course = TeacherCourse::select('teacher_id')->where('course_id', $course_id)->get();
        $teacher = Teacher::whereIn('id', $teacher_course)->get();
        return $teacher;
    }

    public static function getTeacherSchedules($teacher_id){
        $schedules = TeacherSchedule::where('teacher_id', $teacher_id)->get();
        return $schedules;
    }

    public static function getGeneratingTeacherSchedules($teacher_id, $package_id, $course_start, $teacher_schedules=null){
        $schedules_list = TeacherSchedule::where('teacher_id', $teacher_id)->get();
        $package_meet = Package::where('id', $package_id)->first()->number_meet;
        $result = collect();
        $count = 0;
        $sorted_day = Day::sortDays($schedules_list, $course_start);

        if($teacher_schedules != null){
            $schedules = TeacherSchedule::whereIn('id', $teacher_schedules)->where('teacher_id', $teacher_id)->orderByRaw('FIELD(day_id,'.$sorted_day.')')->get();
        }else{
            $schedules = TeacherSchedule::where('teacher_id', $teacher_id)->orderByRaw('FIELD(day_id,'.$sorted_day.')')->get();
        }

        for($i=0; $i < $package_meet; $i++){
            // $start_date = date('Y-m-d',strtotime('+'.$i." weeks"));
            $start_date = date_create($course_start);
            date_modify($start_date, '+'.$i.' week');

            for($j=0; $j<count($schedules); $j++){
                if($count < $package_meet){
                    $date = Day::getStartOfWeekDate($start_date, $schedules[$j]->day_id);
                    $schedule_time = date_format(date_create($date->format('Y-m-d')." ".$schedules[$j]->time_start), "Y-m-d H:i:s");
                    $row = collect();
                    $row->put('schedule_time', $schedule_time);
                    $result->push($row);
                    $count++;
                }
            }
        }

        // Sorting by date
        $sorted = $result->sortBy('schedule_time');
        $result = collect();
        foreach ($sorted as $key){
            $result->push($key);
        }

        return $result;
    }

    public static function setData($user_id, $teacher_subjects){
        $data = new Teacher(array(
            "user_id" => $user_id,
        ));
        $data->save();

        $user = User::where('id', $user_id)->first();
        Log::setLog('MDTCC','Create Teacher : '.$user->name);

        TeacherCourse::setData($data->id, $teacher_subjects);

        return $data;
    }

    public static function bestTeacherThisMonth($sort){
        $data = collect();
        if($sort != 'all'){
            $start = date('Y-m-01'); // hard-coded '01' for first day
            $end  = date('Y-m-t');
        }

        $colors = Color::getColor()->shuffle();
        $i=0;
        $datas = Teacher::where('status', 1)->get();

        foreach($datas as $key){
            $temp = collect();
            $count_orders = Order::whereIn('order_status', [1,2])->where('teacher_id', $key->id);

            if($sort != 'all'){
                $count_orders->whereDate('created_at', ">=", $start)->whereDate('created_at', "<=", $end);
            }

            $count_orders = $count_orders->count();

            $shorted_name = User::shortenName($key->teacher->name);
            if($count_orders != 0){
                $temp->put('teacher_name', $shorted_name);
                $temp->put('order_qty', $count_orders);
                $temp->put('color', $colors[$i]);
                $data->push($temp);
                if($i++ <= 9){
                    $i++;
                }else{
                    $i=0;
                }
            }
        }

        if(count($data) > 10){
            $qtys = array();
            foreach ($data as $key => $row){
                $qtys[$key] = $row['order_qty'];
            }
            array_multisort($qtys, SORT_DESC, $data);
    
            $result = collect();
            for($i=0; $i<10; $i++){
                $result->push($data[$i]);
            }
        }else{
            $result = $data;
        }

        return $result;
    }

    public static function getTeacherList(){
        $result = collect();
        $teachers = Teacher::where('status', 1)->get();
        foreach($teachers as $teacher){
            $description = substr($teacher->description, 0, 50);
            if($teacher->teacher->profilephoto == null){
                $image = asset('dashboard/assets/noimage.jpg');
            }else{
                $image = asset('dashboard/assets/users/photos/'.$teacher->teacher->profilephoto);
            }
            $detail = collect();
            $detail->put('id', $teacher->id);
            $detail->put('title', $teacher->teacher->name);
            $detail->put('subtitle', $teacher->title);
            $detail->put('description', $description);
            $detail->put('image', $image);
            $result->push($detail);
        }
        return json_decode(json_encode($result), FALSE);
    }
}
