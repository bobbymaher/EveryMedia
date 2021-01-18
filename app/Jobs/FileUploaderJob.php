<?php

namespace App\Jobs;

use App\Http\Controllers\UploadController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FileUploaderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $file;
    public $fileHash;
    public $metaData;
    public $userId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file, $fileHash, $metaData, $userId)
    {
        $this->file = $file;
        $this->fileHash = $fileHash;
        $this->metaData = $metaData;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $uploadController = new UploadController();
        $uploadController->processUpload($this->file, $this->fileHash, $this->metaData,  $this->userId);
    }
}
