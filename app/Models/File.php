<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'size',
        'path'
    ];


    // Return a file size in human readable format
    public function sizeForHumans() {
        $bytes = $this->size;

        $units = ['b', 'kb', 'gb', 'tb'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . $units[$i];
    }


    // Run this "booted" function when a new File is created.
    public static function booted() {
        // When this File is created, create a uuid for a file
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }
}
