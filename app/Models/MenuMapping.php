<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        WHERE mm.role_id=$role_id ORDER BY m.urutan ASC");
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
}
