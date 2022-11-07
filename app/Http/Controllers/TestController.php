<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\User;
use App\Models\Teacher;
use App\Models\RoleMapping;

use App\Imports\TeacherImport;

use Excel;

class TestController extends Controller
{
    public function index(){
        $filename = "TeachersList.xlsx"; // pake Avail 3
        $path = public_path($filename);
        $array = Excel::toArray(new TeacherImport, $path);

        $result = array();
        $count = count($array[0]);
        $xls = array_chunk($array[0],$count);

        for ($i=1; $i < $count ; $i++) {
            $names = str_replace(",", "", $xls[0][$i][1]);
            $names = str_replace("Dr.","", $names);
            $names = str_replace("S.Pd.","", $names);
            $names = str_replace("S.Si","", $names);
            $names = str_replace("M.Si.","", $names);
            $names = str_replace("S.T.","", $names);
            $names = str_replace(".","", $names);
            $strings_name = explode(" ", $names);

            $name = $xls[0][$i][1];
            $username = "";
            foreach($strings_name as $key){
                $username .= $key;
            }

            $desc = $xls[0][$i][2];

            if($username != ''){
                $lower = strtolower($username);
                $username_string = $lower;
                $password = $lower;

                $user = new User(array(
                    // Informasi Pribadi
                    'name' => $name,
                    'regis_date' => now(),
                    'username' => $username_string,
                    'password' => Hash::make($password),
                    'bck_pass' => $password,            
                ));
                // success
                $user->save();

                $teacher = new Teacher(array(
                    'user_id' => $user->id,
                    'description' => $desc,
                ));
                $teacher->save();

                RoleMapping::setData($user->username,5);
            }
        }
    }
}
