<?php

namespace App\Jobs;

use App\Models\Media;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Http\Controllers\ThumnbnailController;
use App\Http\Controllers\DiskController;

class ImageThumbnailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $file;
    public $putFileName;
    public $userId;
    public $hash;


    public function __construct($file, $putFileName, $userId, $hash)
    {
        $this->file = $file;
        $this->putFileName = $putFileName;
        $this->userId = $userId;
        $this->hash  = $hash;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $thumbnailBuilder = new ThumnbnailController();

        $this->putFileName .= ThumnbnailController::THUMBNAIL_EXTENSION;
        $thumbnailBuilder->resizeImage($this->file);


        $disk = DiskController::getDisk();
        $disk->putFileAs($this->userId, $this->file,  $this->putFileName);
        $thumbnailBuilder->delete($this->file);

        //set the thumbnail to true so it loads, before this happens it shows the processing thumb
        $media = Media::where('user_id', '=', $this->userId)->where('hash', '=', $this->hash)->first();
        $metaData = $media->meta_data;
        $metaData['thumbnail'] = true;
        $media->meta_data = $metaData;
        $media->save();
    }
}
