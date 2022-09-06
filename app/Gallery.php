<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table ='gallery';
    protected $fillable = [
        'invitation_id', 'title', 'description', 'image', 'creator'
    ];

    public function creator_name(){
        return $this->belongsTo('App\User','creator','id');
    }

    public static function getAllImages(){
        $collection = collect();
        $galleries = Gallery::all();
        foreach($galleries as $gallery){
            $detail = collect();
            $data = collect();

            $tags = GalleryTag::where('gallery_id', $gallery->id)->get();
            foreach($tags as $tag){
                $data->push($tag->tag_name);
            }
            $detail->put('id', $gallery->id);
            $detail->put('invitation_id', $gallery->invitation_id);
            $detail->put('image', $gallery->image);
            $detail->put('title', $gallery->title);
            $detail->put('description', $gallery->description);
            $detail->put('creator', $gallery->creator_name->name);
            $detail->put('tags', $data);
            $collection->push($detail);
        }
        return $collection;
    }

    public static function getGalleries(){
        $collection = collect();
        $galleries = Gallery::all();
        foreach($galleries as $gallery){
            $detail = collect();
            $tags_name = "";

            $tags = GalleryTag::where('gallery_id', $gallery->id)->get();
            foreach($tags as $tag){
                $tags_name .= $tag->tag_name." ";
            }
            $detail->put('id', $gallery->id);
            $detail->put('image', $gallery->image);
            $detail->put('title', $gallery->title);
            $detail->put('description', $gallery->description);
            $detail->put('creator', $gallery->creator_name->name);
            $detail->put('tags', $tags_name);
            $collection->push($detail);
        }
        return $collection;
    }
}
