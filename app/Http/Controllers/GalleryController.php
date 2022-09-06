<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Gallery;
use App\GalleryTag;
use App\Invitation;
use App\Complement;
use App\MenuMapping;
use App\Log;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $galleries = json_decode(json_encode(Gallery::getAllImages()),FALSE);
        // $galleries = Gallery::get();
        $page = MenuMapping::getMap(session('user_id'),"IVGL");

        return view('gallery.index', compact('galleries', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = MenuMapping::getMap(session('user_id'),"IVGL");
        $galleries = Gallery::select('invitation_id')->get();
        $invitation = Invitation::whereNotIn('Invitation_id', $galleries)->get();
        
        return view('gallery.form',compact('page', 'invitation'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'image' => 'required',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $image = "noimage.jpg";
                // Upload Foto
                if($request->image <> NULL|| $request->image <> ''){
                    $image = $request->image->getClientOriginalName();
                    $request->image->move(public_path('multimedia/'.$request->invitation_id.'/galleries/'),$image);
                }

                $galeri = new Gallery(array(
                    // Informasi Pribadi
                    'invitation_id' => $request->invitation_id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'image' => $image,
                    'creator' => session('user_id'),
                ));
                $galeri->save();

                // Tags
                foreach($request->tags as $tag_name){
                    $tag_name = str_replace(' ', '-', $tag_name);
                    $tag = new GalleryTag(array(
                        'tag_name' => $tag_name,
                        'gallery_id' => $galeri->id,
                        'creator' => session('user_id'),
                    ));
                    $tag->save();
                }

                Log::setLog('IVGLC','Create Image: '.$request->title.' ('.$request->invitation_id.')');

                return redirect()->route('gallery.index')->with('status', 'Data berhasil dibuat');
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Galleries
        $galleries = json_decode(json_encode(Gallery::getGalleries()),FALSE);
        $gallery_tags = GalleryTag::GroupBy('tag_name')->get();
        $invitation = Invitation::where('invitation_id',$id)->first();
        $complement = Complement::where('invitation_id', $id)->first();

        return view('gallery.preview', compact('galleries', 'gallery_tags','invitation','complement'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gallery = Gallery::where('id', $id)->first();
        $gallery_tags = GalleryTag::where('gallery_id', $id)->get();
        $invitation = Invitation::all();

        return view('gallery.form', compact('gallery', 'gallery_tags','invitation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
        ]);
        // IF Validation fail
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        // Validation success
        }else{
            try{
                $galeri = Gallery::where('id', $id)->first();

                // Upload Foto
                if($request->image <> NULL|| $request->image <> ''){

                    if (file_exists(public_path('multimedia/'.$request->inv_id.'/galleries/').$galeri->image) && $galeri->image != "noimage.jpg") {
                        unlink(public_path('multimedia/'.$request->inv_id.'/galleries/').$galeri->image);
                    }

                    $image = $request->image->getClientOriginalName();
                    $request->image->move(public_path('multimedia/'.$request->inv_id.'/galleries/'),$image);
                }else{
                    $image = $galeri->image;
                }

                // Tags
                GalleryTag::where('gallery_id', $id)->delete();
                foreach($request->tags as $tag_name){
                    $tag_name = str_replace(' ', '-', $tag_name);
                    $tag = new GalleryTag(array(
                        'tag_name' => $tag_name,
                        'gallery_id' => $id,
                        'creator' => session('user_id'),
                    ));
                    $tag->save();
                }

                $galeri->title = $request->title;
                $galeri->description = $request->description;
                $galeri->image = $image;
                $galeri->creator = session('user_id');
                $galeri->save();

                Log::setLog('IVGLU','Update Image: '.$request->title);

                return redirect()->route('gallery.index')->with('status', 'Data berhasil dibuat');
            }catch(\Exception $e){
                return redirect()->back()->withErrors($e->getMessage());
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $galeri = Gallery::where('id',$id)->first();
        $name = $galeri->name;
        $invitation_id = $galeri->invitation_id;
        if(file_exists(public_path('multimedia/'.$invitation_id.'/galleries/').$galeri->image)){
            unlink(public_path('multimedia/'.$invitation_id.'/galleries/').$galeri->image);
        }
        $galeri->delete();
        Log::setLog('IVGLD','Delete Image: '.$name.' ('.$invitation_id.')');
        return "true";
    }
}
