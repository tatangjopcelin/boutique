<?php

namespace App\Http\Controllers\API;

use App\Models\Video;
use App\Jobs\ConvertVideoForStreaming;
use App\Http\Requests\StoreVideoRequest;
use App\Jobs\ConvertVideoForDownloading;


class VideoController extends Controller
{
    public function store(StoreVideoRequest $request)
    {
        $video = Video::create([

            'disk'          => 'videos_disk',

            'original_name' => $request->video->getClientOriginalName(),

            'path'          => $request->video->store('videos', 'videos_disk'),

            'title'         => $request->title,
        ]);

        //for streamable video
        $this->dispatch(new ConvertVideoForStreaming($video));
        
        //after converting video for streaming, we need to update videos table
        //to add the streamable url

        $streamUrl = Storage::disk('streamable_videos')->url($video->id . '.m3u8');

        $video->update([

            'streamable_url' => $streamUrl,

        ]);

        return response()->json($video,'Video uploaded successfuly');
    }
}