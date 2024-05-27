<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\ConvertVideoForStreaming;
use FFMpeg\Filters\Video\VideoFilters;
use App\Http\Requests\StoreVideoRequest;
use App\Jobs\ConvertVideoForDownloading;
use App\Http\Resources\Video as VideoResource;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoController extends Controller
{
    // public function __construct()
    // {
    //     # code...
    // }

    public function videoRecette($idRecette)
    {
        $video = Video::where('recettes_id',$idRecette)

        ->firstOrFail();

        if (!iss_null($video)) {

           return response()->json(

            new VideoResource($video),

            200
           );
        }
    }
    public function store(StoreVideoRequest $request)
    {
         // get video input file and store
      $video=$request->file("video")
      ->store('storage/videos-recettes','public');

      $pathVideo ='storage/photo-recette/'.$video;
      
         // video manipulation and storable path
         $video = Video::create([

            'recette_id'    => $id_recette,
  
            'path'          => $pathVideo,
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
    public function stocker() {


   // use FFMpeg\Filters\Video\VideoFilters;

    FFMpeg::fromDisk('public')
    ->open('0vg0sPVltECoDvxHuU8ul0qivtTa0fH6n3YYsLID.mp4')
    ->export()
    ->toDisk('converted_videos')
    ->inFormat(new \FFMpeg\Format\Video\X264)
    ->addFilter(function (VideoFilters $filters) {
        $filters->resize(new \FFMpeg\Coordinate\Dimension(640, 480));
    })
    ->save('small_steve.mkv');

        // FFMpeg::fromDisk('public')

        // ->open('0vg0sPVltECoDvxHuU8ul0qivtTa0fH6n3YYsLID.mp4')

    // call the 'exportForHLS' method and specify the disk to which we want to export...
        //->exportForHLS()
        // ->toDisk('public')

    // we'll add different formats so the stream will play smoothly
    // with all kinds of internet connections...
        // ->addFormat($lowBitrateFormat)
        // ->addFormat($midBitrateFormat)
        // ->addFormat($highBitrateFormat)

    // call the 'save' method with a filename...
        // ->save('video_modif.m3u8');
        return response()->json('video convertie',200);
    }
}