<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\MenuMapping;
use App\Employee;
use App\PurchaseMap;
use App\RecycleBin;

class MenuController extends Controller
{

    // Menu Mapping
    public function index(){
        try{
            $users = Employee::all();
            $page = MenuMapping::getMap(session('user_id'),"MRMM");

            return view('menumapping.index',compact('users','page'));
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $page = "menumapping.index";
            return view('helper.error_pages', compact('page', 'error'));
        }
    }

    public function show($id){
        try{
            $currents = json_decode (json_encode(MenuMapping::current($id)),FALSE);
            $rests = json_decode (json_encode(MenuMapping::rest($id)),FALSE);
            $name = Employee::where('id',$id)->first()->name;
            $employees = Employee::where('id', '!=', 1)->where('id', '!=', 28)->get();
            $page = MenuMapping::getMap(session('user_id'),"MRMM");

            return view('menumapping.form',compact('currents','rests','id','name','page','employees'));
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $page = "menumapping.form";
            return view('helper.error_pages', compact('page', 'error'));
        }
    }

    public function store(Request $request){
        $checkbox = $request->rest;
        foreach($checkbox as $rest){
            $store = new MenuMapping(array(
                'user_id' => $request->user_id,
                'submapping_id' => $rest
            ));
            $store->save();
        }

        return redirect()->back();
    }

    public function delete(Request $request){
        $checkbox = $request->current;

        foreach($checkbox as $current){
            $data = MenuMapping::where('submapping_id',$current)->where('user_id',$request->user_id)->first();
            RecycleBin::moveToRecycleBin($data->getTable(), json_encode($data));
            $data->delete();
        }

        return redirect()->back();
    }

    public function copyMenu(Request $request){
        $acuan = $request->employee_id;
        $peniru = $request->user_id;
        $metode = $request->metode;
        $menu_baru = MenuMapping::where('user_id', $acuan)->get();

        if($metode == 1){
            $datas = MenuMapping::where('user_id', $peniru)->get();
            foreach($datas as $data){
                RecycleBin::moveToRecycleBin($data->getTable(), json_encode($data));
            }
            $datas->delete();
        }

        foreach($menu_baru as $menu){
            if(MenuMapping::where('submapping_id', $menu->submapping_id)->where('user_id', $peniru)->count() == 0){
                $store = new MenuMapping(array(
                    'user_id' => $peniru,
                    'submapping_id' => $menu->submapping_id,
                ));
                $store->save();
            }
        }
        return redirect()->back()->with('status', 'Copy Menu berhasil!');
    }

    // Purchase Mapping

    public function PurMapIndex(){
        $users = Employee::all();
        $page = MenuMapping::getMap(session('user_id'),"MRPM");

        return view('purchase.map.index',compact('users','page'));
    }

    public function PurMapShow($id){
        $currents = PurchaseMap::where('employee_id',$id)->get();
        $rests = DB::select("SELECT id,nama FROM tblperusahaan WHERE id NOT IN (SELECT supplier_id FROM map_purchase WHERE employee_id = $id)");
        $rests = json_decode (json_encode($rests),FALSE);
        $name = Employee::where('id',$id)->first()->name;
        return view('purchase.map.form',compact('currents','rests','id','name'));
    }

    public function PurMapStore(Request $request){
        $checkbox = $request->rest;
        foreach($checkbox as $rest){
            $store = new PurchaseMap(array(
                'employee_id' => $request->user_id,
                'supplier_id' => $rest
            ));
            $store->save();
        }

        return redirect()->back();
    }

    public function PurMapDelete(Request $request){
        $checkbox = $request->current;

        foreach($checkbox as $current){
            $data = PurchaseMap::where('supplier_id',$current)->where('employee_id',$request->user_id)->first();
            RecycleBin::moveToRecycleBin($data->getTable(), json_encode($data));
            $data->delete();
        }

        return redirect()->back();
    }
}
