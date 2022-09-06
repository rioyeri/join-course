<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\DB;

use App\MenuMapping;
use App\User;

class MenuController extends Controller
{

    // Menu Mapping
    public function index(){
        $users = User::all();
        $page = MenuMapping::getMap(session('user_id'),"MRMM");

        return view('menumapping.index',compact('users','page'));
    }

    public function show($id){
        $currents = json_decode (json_encode(MenuMapping::current($id)),FALSE);
        $rests = json_decode (json_encode(MenuMapping::rest($id)),FALSE);
        $name = User::where('id',$id)->first()->name;
        return view('menumapping.form',compact('currents','rests','id','name'));
    }

    public function store(Request $request){
        try{
            $checkbox = $request->rest;
            foreach($checkbox as $rest){
                $store = new MenuMapping(array(
                    'user_id' => $request->user_id,
                    'submapping_id' => $rest
                ));
                $store->save();
            }

            return redirect()->back();
        }catch(\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function delete(Request $request){
        try{
            $checkbox = $request->current;

            foreach($checkbox as $current){
                $store = MenuMapping::where('submapping_id',$current)->where('user_id',$request->user_id)->first();

                $store->delete();
            }

            return redirect()->back();
        }catch(\Exception $e){
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
