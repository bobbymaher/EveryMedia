<?php

namespace App\Http\Controllers;


use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{

    private $disk;
    const TEMP_URL_EXPIRY_MINS = 1440;


    public function __construct()
    {
        $this->disk = DiskController::getDisk();
    }


    public function getFiles(): array
    {
        $files = Media::where('user_id', '=', Auth::user()->uuid)->get();

        $response = [];
        foreach ($files as $file) {

            $file->url = $this->getTemporaryUrl($file);

            if ($file->meta_data['thumbnail'] === true) {
                $file->thumbnail = $this->getTemporaryUrl($file, true);
            } else {

                $file->thumbnail = match ($file->meta_data['content_type']) {
                    'audio' => ThumnbnailController::AUDIO_DEFAULT_THUMB,
                    'video' => ThumnbnailController::PROCESSING_THUMB,
                    'other' => ThumnbnailController::OTHER_THUMB,
                    default => ThumnbnailController::ERROR_THUMB,
                };
            }

            $response[] = $file;
        }
        return $response;
    }




    /**
     * @param string $file
     * @param bool $thumbNail
     * @return string
     */
    public function getTemporaryUrl(\App\Models\Media $file, bool $thumbNail = false): string
    {

        $extension = $file->meta_data['extension'];
        if ($thumbNail) {
            $extension = $extension . ThumnbnailController::THUMBNAIL_EXTENSION;
        }

        $pathAndFile = Media::generatePath(Auth::user()->uuid, $file->hash, $extension);

        return $this->disk->temporaryUrl($pathAndFile, now()->addMinutes(self::TEMP_URL_EXPIRY_MINS));
    }


    public function delete(Request $request): \Illuminate\Http\JsonResponse
    {

        $userId = Auth::user()->uuid;

        $validator = Validator::make($request->all(), [
            'hash' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $hash = $request->hash;

        $info = [];

        $file = Media::where('user_id', '=', $userId)->where('hash', '=', $hash)->first();

        if ($file) {
            $fileName = $userId . '/' . $file->hashedFileName();
            if ($file->meta_data['thumbnail'] === true) {
                $this->disk->delete($fileName . ThumnbnailController::THUMBNAIL_EXTENSION);
                $info['thumbnail_removed'] = true;
            } else {
                $info['thumbnail_removed'] = false;
            }
            $info['file_name'] = $file->name;
            $info['file_deleted'] = $fileName;
            $this->disk->delete($fileName);
            $file->delete();
        }

        $response = [
            'success' => true,
            'info' => $info,
        ];

        return response()->json($response);
    }




    public function rename(Request $request): \Illuminate\Http\JsonResponse
    {

        $userId = Auth::user()->uuid;

        $validator = Validator::make($request->all(), [
            'hash' => 'required|string',
            'value' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $hash = $request->hash;

        $file = Media::where('user_id', '=', $userId)->where('hash', '=', $hash)->first();

        if ($file) {
           $file->name = $request->value;
           $file->save();
        }

        $response = [
            'success' => true,
        ];

        return response()->json($response);
    }



    public static function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
