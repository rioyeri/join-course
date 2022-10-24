<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ContentHome extends Model
{
    protected $table ='content_home';
    protected $fillable = [
        'segment','title','subtitle','status','creator'
    ];

    public static function dataIndex(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $page = MenuMapping::getMap(session('role_id'),"CTHO");
        $home = ContentHome::select('id','segment','title','subtitle','status','creator');

        $totalRecords = $home->count();

        if($searchValue != ''){
            $home->where(function ($query) use ($searchValue) {
                $query->orWhere('segment', 'LIKE', '%'.$searchValue.'%')->orWhere('title', 'LIKE', '%'.$searchValue.'%')->orWhere('subtitle', 'LIKE', '%'.$searchValue.'%');
            });
        }

        $totalRecordwithFilter = $home->count();

        if($columnName == "no"){
            $home->orderBy('id', $columnSortOrder);
        }else{
            $home->orderBy($columnName, $columnSortOrder);
        }

        $home = $home->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($home as $key){
            $detail = collect();

            $j = 1;
            $contents = '';
            $contents .= '<li> <strong>Title</strong> : '.$key->title.'</li>';
            $contents .= '<li> <strong>Subtitle</strong> : '.$key->subtitle.'</li>';    

            if($key->id != 1){
                $details = ContentHomeDetail::where('content_id', $key->id)->get();
                if(count($details) != 0){
                    $contents .= '<li> <strong>Contents</strong> :</li>';
                }
                foreach($details as $det){
                    if($key->id == 3){
                        $teacher = Teacher::where('id', $det->link)->first();
                        if($teacher->title != null){
                            $subtitle = '('.$teacher->title.')';
                        }else{
                            $subtitle = "";
                        }
                        $contents .= '<ul><strong>'.$j++.'. '.$teacher->teacher->name.' '.$subtitle.'</strong></ul>';
                        $contents .= '<ul>'.$teacher->description.'</ul>';
                    }else{
                        if($det->subtitle != null){
                            $subtitle = '('.$det->subtitle.')';
                        }else{
                            $subtitle = "";
                        }
                        $contents .= '<ul><strong>'.$j++.'. '.$det->title.' '.$subtitle.'</strong></ul>';
                        $contents .= '<ul>'.$det->description.'</ul>';
                    }
                }
            }

            $options = '';
            if (array_search("CTHOU",$page)){
                $options .= '<a class="btn btn-primary btn-block btn-round m-5" onclick="edit_data('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> Edit</a> ';
            }
            if (array_search("CTHOS",$page)){
                if($key->status == 0){
                    $options .= '<a class="btn btn-warning btn-block btn-round m-5" onclick="change_status('.$key->id.')"><i class="fa fa-power-off"></i> Non-Active</a> ';
                }else{
                    $options .= '<a class="btn btn-success btn-block btn-round m-5" onclick="change_status('.$key->id.')"><i class="fa fa-power-off"></i> Active</a> ';
                }
            }

            $detail->put('no', $i++);
            $detail->put('segment', $key->segment);
            $detail->put('contents', $contents);
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

    public static function getContent(){
        $result = collect();
        $contents = ContentHome::where('status', 1)->get();
        
        foreach($contents as $content){
            $row = collect();
            $row_detail = collect();
            $contdet = ContentHomeDetail::where('content_id', $content->id)->get();
            foreach($contdet as $det){
                if($det->image == null){
                    $image = asset('dashboard/assets/noimage.jpg');
                }else{
                    if($content->id == 3){
                        $teacher_image = Teacher::where('id', $det->link)->first()->teacher->profilephoto;
                        $image = asset('dashboard/assets/users/photos/'.$teacher_image);
                    }elseif($content->id == 4){
                        $image = asset('landingpage/assets/testimonial/photos/'.$det->image);
                    }else{
                        $image = $det->image;
                    }
                }

                if($content->id == 3){
                    $teacher = Teacher::where('id', $det->link)->first();
                    $title = $teacher->teacher->name;
                    $subtitle = $teacher->title;
                    $description = $teacher->description;
                    $link = $det->link;
                    $link_text = "";
                }else{
                    $title = $det->title;
                    $subtitle = $det->subtitle;
                    $description = $det->description;
                    $link = $det->link;
                    $link_text = $det->link_text;
                }
                $detail = collect();
                $detail->put('title', $title);
                $detail->put('subtitle', $subtitle);
                $detail->put('description', $description);
                $detail->put('image', $image);
                $detail->put('link', $link);
                $detail->put('link_text', $link_text);
                $row_detail->push($detail);
            }
            $row->put('title', $content->title);
            $row->put('subtitle', $content->subtitle);
            $row->put('detail', $row_detail);
            $result->push($row);
        }

        return json_decode(json_encode($result), FALSE);
    }
}
