<?php

namespace App\Http\Controllers;

use App\Jobs\VideoProcess;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
//import GD dan Intervention
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Gd\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

//install library
//composer require intervention/image
//composer require pbmedia/laravel-ffmpeg

class VideoApiController extends Controller
{
    public function index(){
        $data = Video::all();
        return response()->json(['message' => "Menampilkan semua video", 'success' => true, 'data' => $data], 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'thumbnail' => 'required|image|mimes:png,jpg,jpeg',
            'video' => 'required|mimes:mp4'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->all(), 'success' => false], 422);
        }

        $manager = new ImageManager(new Driver()); //initialize driver GD
        $image = $manager->read($request->file('thumbnail'));
        $image->scaleDown(1080); //scale image ke 1080p

        $thumbnail = $image->encode(new WebpEncoder(quality: 80)); //encode jadi webEncoder dan qualit 80(bisa di ubah sesuai kebutuhan)
        $nmthumbnail = "thumbnail_" . time() . '.webp';
        $thumbnailPath = "thumbnails/" . $nmthumbnail;
        Storage::disk('public')->put($thumbnailPath, $thumbnail);

        $videoRaw = $request->file('video');
        $nmvideoRaw = 'video_' . time() . '.' . $videoRaw->getClientOriginalExtension();
        $videoRawPath = $videoRaw->storeAs('videos',$nmvideoRaw,'public');

        $video = Video::create([
            'title' => $request->title,
            'thumbnail_path' => $thumbnailPath,
            'video_raw_path' => $videoRawPath
        ]);
        VideoProcess::dispatch($video->id);
        return response()->json(['message' => "Video berhasil di upload", 'success' => true], 201);
    }
}
