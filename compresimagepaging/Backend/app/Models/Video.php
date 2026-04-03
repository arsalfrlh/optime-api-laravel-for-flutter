<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = "video";
    protected $fillable = ['title','thumbnail_path','video_raw_path','video_path','duration'];
}
