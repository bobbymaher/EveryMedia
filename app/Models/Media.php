<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;


    protected $casts = [
        'meta_data' => 'array'
    ];


    public static function generatePath($userId, $hash, $extension)
    {
        return $userId . '/' . $hash . '.' . $extension;
    }


    public function hashedFileName() {
        return $this->hash . '.' . $this->meta_data['extension'];
    }
}
