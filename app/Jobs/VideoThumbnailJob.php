<?php

namespace App\Jobs;

use App\Http\Controllers\ThumnbnailController;
use App\Http\Controllers\UploadController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class VideoThumbnailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $thumbNail = $thumbnailBuilder->generateThumbnailFromVideo($this->file);
        $thumbnailBuilder->delete($this->file);

        ImageThumbnailJob::dispatch($thumbNail, $this->putFileName,$this->userId, $this->hash);

    }
}
