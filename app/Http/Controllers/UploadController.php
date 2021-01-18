<?php

namespace App\Http\Controllers;

use App\Jobs\FileUploaderJob;
use App\Jobs\ImageThumbnailJob;
use App\Jobs\VideoThumbnailJob;

use App\Models\Media;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;




class UploadController extends Controller
{

    private $disk;

    public function __construct()
    {
        $this->disk = DiskController::getDisk();
    }


    /** test for now  - could be used by a cron job*/
    public function scanDirectoryForFiles(): void
    {
        ini_set('memory_limit', '-1');
        $files = scandir(resource_path('temp'));
        foreach ($files as $fileName) {

            if ($fileName != "." && $fileName != "..") {
                $this->uploadFile($fileName);
            }
        }
    }


    /**
     * @param string $fileName
     * @return bool
     */
    public function uploadFile(string $fileName): bool
    {

        $file = storage_path('uploads') . '/' . $fileName;
        $fileHash = sha1_file($file);

        $existingFile = Media::where('user_id', '=', Auth::user()->uuid)->where('hash', '=', $fileHash)->first();

        if (!empty($existingFile)) {
            echo "This is a duplicate of $fileName";
            return false;
        } else {

            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $cleanFileName = pathinfo($fileName, PATHINFO_FILENAME);;
            $mimeType = mime_content_type($file);
            $contentType = $this->contentType($mimeType);

            $metaData = [
                'size' => filesize($file),
                'mime_type' => $mimeType,
                'content_type' => $contentType,
                'extension' => $extension,
                'thumbnail' => false,
            ];

            $media = new Media();
            $media->user_id = Auth::user()->uuid;
            $media->name = $cleanFileName;
            $media->hash = $fileHash;
            $media->meta_data = $metaData;
            $media->available = false;
            $media->save();


            FileUploaderJob::dispatch($file, $fileHash, $metaData, Auth::user()->uuid);



            return true;

        }
    }


    /**
     * @param string $mimeType
     * @return string
     */
    private function contentType(string $mimeType): string
    {
        $contentType = 'other';

        if (Str::startsWith($mimeType, 'video')) {
            $contentType = 'video';
        } elseif (Str::startsWith($mimeType, 'audio')) {
            $contentType = 'audio';
        } elseif (Str::startsWith($mimeType, 'image')) {
            $contentType = 'image';
        }

        return $contentType;
    }



    /**
     * This is ran on the queue
     *
     * @param string $file
     * @param string $fileHash
     * @param array $metaData
     * @param string $userId
     */
    public function processUpload(string $file, string $fileHash, array $metaData, string $userId) : void
    {

        // save the file
        $putFileName = $fileHash . '.' . $metaData['extension'];
        $this->disk->putFileAs($userId, $file, $putFileName);

        $media = Media::where('user_id', '=', $userId)->where('hash', '=', $fileHash)->first();
        $media->available = 1;
        $media->save();

        //generate a thumbnail for video / images
        if (Str::startsWith($metaData['content_type'], 'video')) {

            VideoThumbnailJob::dispatch($file, $putFileName, $userId, $fileHash);
        } elseif (Str::startsWith($metaData['content_type'], 'image')) {

            ImageThumbnailJob::dispatch($file, $putFileName, $userId, $fileHash);
        } elseif (Str::startsWith($metaData['content_type'], 'audio')) {
            /*
             *
             * @todo get id3 tag for mp3's
            $thumbnailBuilder = new ThumnbnailController();
            $thumbNail = $thumbnailBuilder->extractId3FromMp3($file);
            $metaData['thumbnail'] = true;
            */
        }
    }



    //basic uploader, to be replaced with chunked uploader

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fileUpload(Request $request)
    {
        /*
         * //for now we accept all file types. probably risky as someone could upload an executable/scripts

        $request->validate([
            'file' => 'required|mimes:pdf,xlx,csv|max:2048',
        ]);
        */

        $fileName = $request->file->getClientOriginalName();

        $request->file->move(storage_path('uploads'), $fileName);

        $this->uploadFile($fileName);

        return back()
            ->with('success', 'You have successfully upload file.')
            ->with('file', $fileName);

    }




}
