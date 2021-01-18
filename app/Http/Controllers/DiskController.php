<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class DiskController extends Controller
{

    //only s3 for now, but putting it here so its configured in one place
    public static function getDisk()
    {
        return Storage::disk('s3');
    }
}
