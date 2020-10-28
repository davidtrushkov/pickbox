<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];


    // Run this "booted" function when a new Folder is created.
    public static function booted() {
        // When this Folder is created, create a uuid for a folder
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }
}
