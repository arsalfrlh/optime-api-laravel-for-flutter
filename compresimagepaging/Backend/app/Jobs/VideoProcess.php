<?php

namespace App\Jobs;

use App\Models\Video;
use FFMpeg\Format\Video\X264;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoProcess implements ShouldQueue
{
    use Queueable, SerializesModels, Dispatchable;
    protected $videoId;

    /**
     * Create a new job instance.
     */
    public function __construct($videoId)
    {
        $this->videoId = $videoId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $video = Video::findOrFail($this->videoId);
        $rawVideo = $video->video_raw_path;
        $hlsPath = "hls/video_" . $video->id;
        $m3u8Name = "playlist.m3u8";
        $format = new X264('aac', 'libx264');
        $format->setAudioCodec('aac');
        FFMpeg::fromDisk('public')
            ->open($rawVideo)
            ->exportForHLS()
            ->setSegmentLength(10)
            ->addFormat($format)
            ->toDisk('public')
            ->save($hlsPath . '/' . $m3u8Name);
        $duration = FFMpeg::fromDisk('public')
            ->open($rawVideo)
            ->getDurationInSeconds();
        $video->update([
            'video_path' => $hlsPath . '/' . $m3u8Name,
            'duration' => $duration
        ]);
        Storage::disk('public')->delete($rawVideo);
    }
}
