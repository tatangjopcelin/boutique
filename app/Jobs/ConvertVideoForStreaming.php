<?php

namespace App\Jobs;


use FFMpeg;
use Carbon\Carbon;
use App\Models\Video;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use ProtoneMedia\LaravelFFMpeg\FFMpeg\ProgressListenerDecorator;

class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function handle()
    {
        // create some video formats...
        $lowBitrateFormat  = (new X264)->setKiloBitrate(500);
        $midBitrateFormat  = (new X264)->setKiloBitrate(1500);
        $highBitrateFormat = (new X264)->setKiloBitrate(3000);

        $format = new X264;

        $decoratedFormat = ProgressListenerDecorator::decorate($format);


        // open the uploaded video from the right disk...
        FFMpeg::fromDisk('public')

            ->open($this->video->path)

        // call the 'exportForHLS' method and specify the disk to which we want to export...
            ->exportForHLS()

            ->toDisk('streamable_videos','public')

            // ->inFormat($decoratedFormat)

            // ->onProgress(function () use ($decoratedFormat) {

            //     $listeners = $decoratedFormat->getListeners();  // array of listeners

            //     $listener = $listeners[0];  // instance of AbstractProgressListener

            //     $listener->getCurrentPass();
            //     $listener->getTotalPass();
            //     $listener->getCurrentTime();
                
            // })
        // we'll add different formats so the stream will play smoothly
        // with all kinds of internet connections...
            ->addFormat($lowBitrateFormat)
            ->addFormat($midBitrateFormat)
            ->addFormat($highBitrateFormat)

        // call the 'save' method with a filename...
            ->save($this->video->id .'.m3u8');

            $streamUrl = Storage::disk('streamable_videos','public')->url($video->id . '.m3u8');


        // update the database so we know the convertion is done!
        $this->video->update([

            'streamable_url' => $this->apiUrl.''.$streamUrl,

            'converted_for_streaming_at' => Carbon::now(),
        ]);
    }
}
