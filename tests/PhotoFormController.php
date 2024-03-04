<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Photo;

class PhotoFormController extends Controller
{
    public function show()
    {
        return view("index.php");
    }

    function upload(Request $request){
        $request->validate([
            'image' => 'required|file|image|mimes:png,jpeg'
        ]);
        $upload_image = $request->file('image');

        if($upload_image) {
            //アップロードされた画像を保存する
            $path = $upload_image->store('uploads',"public");
            //画像の保存に成功したらDBに記録する
            if($path){
                Photo::create([
                    "file_name" => $upload_image->getClientOriginalName(),
                    "file_path" => $path
                ]);
            }
        }
        return redirect("tomoviews/photo");;
    }

    function photo()
    {
        //アップロードした画像を取得
        $uploads = Photo::orderBy("id", "desc")->get();

        return view("tomoviews.photo",[
            "images" => $uploads
        ]);
    }
