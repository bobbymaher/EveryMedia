<?php

namespace App\Http\Controllers;


use FFMpeg;

use Owenoj\LaravelGetId3\GetId3;

use Intervention\Image\Facades\Image;

class ThumnbnailController extends Controller
{

    const THUMBNAIL_EXTENSION = '.thumbnail.jpg';

    const AUDIO_DEFAULT_THUMB = '/images/audio.png';
    const OTHER_THUMB = '/images/unknown.png';
    const ERROR_THUMB = '/images/error.png';
    const PROCESSING_THUMB = '/images/processing.png';


    public function generateThumbnailFromVideo(string $file)
    {

        $thumbFile = $this->convertVideoToThumbnail($file);

        return $thumbFile;
    }

    public function delete(string $file){

        if (\File::exists($file)) {
            \File::delete($file);
        }
    }


    private function convertVideoToThumbnail(string $file)
    {
        $ffmpeg = \FFMpeg\FFMpeg::create([
            'ffmpeg.binaries' => '/usr/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/bin/ffprobe'
        ]);

        $thumbFile = storage_path('framework') . '/cache/' . sha1_file($file) . '.jpg';

        echo $thumbFile;

        $video = $ffmpeg->open($file);
        $video
            ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(1)) // dont set seconds too high as the video could be short!
            ->save($thumbFile);

        return $thumbFile;
    }


    public function extractId3FromMp3(string $file) {

        //@todo use an id3 lib to get the album artwork
    }

    public function resizeImage(string $fileName, string $outputFile = '', int $width = 400, int $height = 400)
    {

        if(empty($outputFile)){
            $outputFile = $fileName;
        }

        Image::make($fileName)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($outputFile);
    }
}
