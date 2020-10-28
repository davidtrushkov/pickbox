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
        'size'
    ];


    // Run this "booted" function when a new File is created.
    public static function booted() {
        // When this File is created, create a uuid for a file
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }
}
