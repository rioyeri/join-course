<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


use App\Models\SubModul;
use App\Models\SubMapping;

class MenuMapping extends Model
{
    protected $table ='role_submapping';
    protected $fillable = [
        'role_id', 'submapping_id'
    ];

    public static function current($user){
        $modul = DB::select("SELECT DISTINCT m.modul_desc,s.submodul_id,s.submodul_desc FROM role_submapping mm
        INNER JOIN modul_submapping sm ON sm.id = mm.submapping_id 
        INNER JOIN modul_submodul s ON s.submodul_id = sm.submodul_id
        INNER JOIN modul m ON m.modul_id = s.modul_id
        WHERE mm.user_id =$user");
        $data = collect();
        foreach ($modul as $mod) {
            $current = collect($mod);
            $submap = DB::select("SELECT submapping_id,jenis_id FROM role_submapping 
            INNER JOIN modul_submapping ON role_submapping.submapping_id = modul_submapping.id
            WHERE user_id=$user AND modul_submapping.submodul_id = '$mod->submodul_id'");
            $current->put('submapping',$submap);
            $data->push($current);
        }
        return $data;
    }

    public static function rest($user){
        $submodul = SubModul::all();
        $data = collect();

        foreach ($submodul as $submod) {
            $check_original = SubMapping::where('submodul_id',$submod->submodul_id)->count();
            $check_current = MenuMapping::join('modul_submapping','modul_submapping.id','=','role_submapping.submapping_id')->where('user_id',$user)->where('modul_submapping.submodul_id',$submod->submodul_id)->count();

            if($check_original != $check_current){
                $rest = DB::select("SELECT id,jenis_id FROM modul_submapping
                WHERE modul_submapping.id NOT IN (SELECT role_submapping.submapping_id FROM role_submapping WHERE role_submapping.user_id = $user) AND modul_submapping.submodul_id = '$submod->submodul_id'");
                $subcollect = collect();
                $subcollect->put('modul',$submod->modul->modul_desc);
                $subcollect->put('submodul',$submod->submodul_desc);
                $subcollect->put('submapping',$rest);
                $data->push($subcollect);
            }
        }

        return $data;
    }

    public function submodul(){
        return $this->belongsTo('App\SubModul','submodul_id','submodul_id');
    }

    public static function getModul($role_id,$modul=null){
        return DB::select("SELECT DISTINCT m.modul_desc,m.modul_icon,m.modul_id,s.submodul_id,s.submodul_desc,s.submodul_page FROM role_submapping mm
        INNER JOIN modul_submapping sm ON sm.id = mm.submapping_id 
        INNER JOIN modul_submodul s ON s.submodul_id = sm.submodul_id
        INNER JOIN modul m ON m.modul_id = s.modul_id
        WHERE mm.role_id =$role_id AND m.modul_id like '$modul%' ORDER BY s.urutan ASC");
    }

    public static function getHeadModul($role_id){
        return DB::select("SELECT DISTINCT m.modul_desc,m.modul_icon,m.modul_id FROM role_submapping mm
        INNER JOIN modul_submapping sm ON sm.id = mm.submapping_id 
        INNER JOIN modul_submodul s ON s.submodul_id = sm.submodul_id
        INNER JOIN modul m ON m.modul_id = s.modul_id
        WHERE mm.role_id=$role_id AND m.modul_id!='DS' ORDER BY m.urutan ASC");
    }

    public static function getMap($role_id,$submodul){
        $data = collect();
        
        if($role_id == 1){
            foreach(SubMapping::where('submodul_id','LIKE',$submodul.'%')->get() as $key){
                $data->put($key->id,$key->id);
            }
        }else{
            foreach(MenuMapping::where('role_id',$role_id)->where('submapping_id','LIKE',$submodul.'%')->get() as $key){
                $data->put($key->submapping_id,$key->submapping_id);
            }
        }
        
        return $data->toArray();
    }

    public static function dataIndex(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $page = MenuMapping::getMap(session('role_id'),"USMM");
        $menumapping = Role::select('id','name','description');

        $totalRecords = $menumapping->count();

        if($searchValue != ''){
            $menumapping->where('r.name', 'LIKE', '%'.$searchValue.'%');
        }

        $totalRecordwithFilter = $menumapping->count();

        if($columnName == "no"){
            $menumapping->orderBy('id', $columnSortOrder);
        }else{
            $menumapping->orderBy($columnName, $columnSortOrder);
        }

        $menumapping = $menumapping->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($menumapping as $key){
            $detail = collect();

            $count_menu = MenuMapping::where('role_id', $key->id)->count();
            $options = '';
            if (array_search("USMMU",$page)){
                $options .= '<a class="btn btn-theme btn-round m-5" onclick="edit_data('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-gears"></i> Have '.$count_menu.' Access Menu</a> ';
            }

            $detail->put('no', $i++);
            $detail->put('role_name', $key->name);
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

    public static function getAllSubmodul(){
        $moduls = Modul::getAllModul();
        $result = collect();
        foreach($moduls as $modul){
            $row1 = collect();
            $submodul = SubModul::where('modul_id', $modul->modul_id)->get();
            $detail = collect();
            foreach($submodul as $sm){
                $row2 = collect();
                $submapping = SubMapping::where('submodul_id', $sm->submodul_id)->get();
                $detail2 = collect();
                foreach($submapping as $mp){
                    $row3 = collect();
                    $row3->put('submapping_id', $mp->id);
                    $row3->put('jenis_id', $mp->jenis_id);
                    $detail2->push($row3);
                }
                $row2->put('submodul_name', $sm->submodul_desc);
                $row2->put('submappings', $detail2);
                $detail->push($row2);
            }
            $row1->put('modul_name', $modul->modul_desc);
            $row1->put('submoduls', $detail);
            $result->push($row1);
        }
        return $result;
    }

    public static function setData($id, $menus){
        $role = Role::where('id', $id)->first();
        $old_list = MenuMapping::where('role_id', $id)->get();
        if($old_list->count() != 0){
            foreach($old_list as $list){
                $status = 0;
                foreach($menus as $menu){
                    if($list->submapping_id == $menu){
                        $status++;
                    }
                    if(MenuMapping::where('role_id', $id)->where('submapping_id', $menu)->count() == 0){
                        $new = new MenuMapping(array(
                            "role_id" => $id,
                            "submapping_id" => $menu,
                        ));
                        $new->save();
                        Log::setLog('USMMC','Create Menu : '.$menu.' to '.$role->name);
                    }
                }
                if($status == 0){
                    if(MenuMapping::where('role_id', $id)-> where('submapping_id', $list->submapping_id)->count() != 0){
                        $log_id = Log::setLog('USMMD','Delete Menu : '.$list->submapping_id.' to '.$role->name);
                        RecycleBin::moveToRecycleBin($log_id, $list->getTable(), json_encode($list));
                        $list->delete();
                    }
                }
            }
        }else{
            foreach($menus as $menu){
                if(MenuMapping::where('role_id', $id)->where('submapping_id', $menu)->count() == 0){
                    $new = new MenuMapping(array(
                        "role_id" => $id,
                        "submapping_id" => $menu,
                    ));
                    $new->save();
                    Log::setLog('USMMC','Create Menu : '.$menu.' to '.$role->name);
                }
            }
        }

    }
}
