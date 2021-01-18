<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MetadataController extends Controller
{

    public function getMetaData(string $file) {

    }


    public function getMimeTypeOfFile(string $file){
        $mimeType = mime_content_type($file);
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
}
