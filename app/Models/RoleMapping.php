<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class RoleMapping extends Model
{
    protected $table ='users_role';
    protected $fillable = [
        'username', 'role_id', 'creator'
    ];

    public function role(){
        return $this->belongsTo('App\Models\Role');
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'username', 'username');
    }

    public function nonAdmin(){
        $d = array_values(array_column(DB::select("SELECT u.id FROM users as u
        INNER JOIN users_role as ur on u.username = ur.username
        INNER JOIN role as r ON r.id = ur.role_id
        WHERE r.name REGEXP 'Superadmin|Owner|Admin'"),'id'));

        return $d;
    }

    public static function getUserIdByRole($role_id){
        $d = array_values(array_column(DB::select("SELECT u.id FROM users as u
        INNER JOIN users_role as ur on u.username = ur.username
        WHERE ur.role_id = $role_id"),'id'));

        return $d;
    }

    public static function setData($username, $option){
        if(RoleMapping::where('username', $username)->count() != 0){
            RoleMapping::where('username', $username)->update(array(
                'role_id' => $option,
            ));
        }else{
            $mapping = new RoleMapping(array(
                'username' => $username,
                'role_id' => $option,
            ));
            $mapping->save();
        }
    }

    public static function dataIndex(Request $request){
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request['order'][0]['column']; // Column index
        $columnName = $request['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $request['order'][0]['dir']; // asc or desc
        $searchValue = $request['search']['value']; // Search value

        $page = MenuMapping::getMap(session('role_id'),"USRM");
        $rolemapping = RoleMapping::join('users as u', 'users_role.username', 'u.username')->join('role as r', 'users_role.role_id', 'r.id')->select('users_role.id','users_role.username','u.name as user_name','users_role.role_id','r.name as role_name');

        $totalRecords = $rolemapping->count();

        if($searchValue != ''){
            $rolemapping->where(function ($query) use ($searchValue) {
                $query->orWhere('u.name', 'LIKE', '%'.$searchValue.'%')->orWhere('r.name', 'LIKE', '%'.$searchValue.'%');
            });
        }

        $totalRecordwithFilter = $rolemapping->count();

        if($columnName == "no"){
            $rolemapping->orderBy('id', $columnSortOrder);
        }else{
            $rolemapping->orderBy($columnName, $columnSortOrder);
        }

        $rolemapping = $rolemapping->offset($row)->limit($rowperpage)->get();

        $data = collect();
        $i = $row+1;

        foreach($rolemapping as $key){
            $detail = collect();

            $options = '';

            if (array_search("USRMU",$page)){
                $options .= '<a class="btn btn-primary btn-round m-5" onclick="edit_data('.$key->id.')" data-toggle="modal" data-target="#myModal"><i class="fa fa-pencil"></i> Edit</a> ';
            }

            if (array_search("USRMD",$page)){
                $options .= '<a href="javascript:;" class="btn btn-danger btn-round m-5" onclick="delete_data('.$key->id.')"><i class="fa fa-trash-o"></i> Delete</a>';
            }

            $detail->put('no', $i++);
            $detail->put('user_name', $key->user_name);
            $detail->put('role_name', $key->role_name);
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
}
