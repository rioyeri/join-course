<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PackageGrade extends Model
{
    use SoftDeletes;
    protected $table ='package_grade';
    protected $fillable = [
        'package_id', 'grade_id',
    ];

    public function get_package(){
        return $this->belongsTo('App\Models\Package', 'package_id', 'id');
    }

    public function get_grade(){
        return $this->belongsTo('App\Models\Grade', 'grade_id', 'id');
    }
}
