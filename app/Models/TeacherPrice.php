<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherPrice extends Model
{
    protected $table ='teacher_price';
    protected $fillable = [
        'teacher_id','package_id','price','creator'
    ];

    public function teacher(){
        // return $this->belongsTo('App\Models\Teacher', 'teacher_id', 'id');
        $teacher = Teacher::where('id', $this->teacher_id)->first()->teacher();
        return $teacher;
    }

    public function get_package(){
        return $this->belongsTo('App\Models\Package', 'package_id', 'id');
    }

    public static function setData($id, $package_ids=null, $package_prices=null){
        $old_list = TeacherPrice::where('teacher_id', $id)->get();
        if($old_list->count() != 0){
            foreach($old_list as $list){
                $status = 0;
                if($package_ids){
                    for($i=0; $i < count($package_ids); $i++){
                        if($list->package_id == $package_ids[$i]){
                            $status++;
                        }
                        if(TeacherPrice::where('teacher_id', $id)->where('package_id', $package_ids[$i])->count() == 0){
                            $new = new TeacherPrice(array(
                                "teacher_id" => $id,
                                "package_id" => $package_ids[$i],
                                "price" => $package_prices[$i],
                            ));
                            $new->save();
                            Log::setLog('MDTCC','Create Teachers : '.$list->teacher->name.', Package : '.$list->get_package->name);
                        }else{
                            $data = TeacherPrice::where('teacher_id', $id)->where('package_id', $package_ids[$i])->first();
                            $data->price = $package_prices[$i];
                            $data->save();
                        }
                    }
                }
                if($status == 0){
                    if(TeacherPrice::where('teacher_id', $id)-> where('package_id', $list->package_id)->count() != 0){
                        $log_id = Log::setLog('MDTCD','Delete Teachers : '.$list->teacher->name.', Package : '.$list->get_package->name);
                        RecycleBin::moveToRecycleBin($log_id, $list->getTable(), json_encode($list));
                        $list->delete();
                    }
                }
            }
        }else{
            for($i=0; $i < count($package_ids); $i++){
                if(TeacherPrice::where('teacher_id', $id)->where('package_id', $package_ids[$i])->count() == 0){
                    $teacher = Teacher::where('id', $id)->first();
                    $package = Package::where('id', $package_ids[$i])->first();
                    $new = new TeacherPrice(array(
                        "teacher_id" => $id,
                        "package_id" => $package_ids[$i],
                        "price" => $package_prices[$i],
                    ));
                    $new->save();
                    Log::setLog('MDTCC','Create Teachers : '.$teacher->teacher->name.', Package : '.$package->name);
                }
            }
        }

    }
}
