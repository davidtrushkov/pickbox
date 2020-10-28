<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obj extends Model
{
    use HasFactory;

    public $table = 'objects';

    protected $fillable = [
        'parent_id'
    ];


    // Run this "booted" function when a new Object is created.
    public static function booted() {
        // When this Object is created, create a uuid for a object
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }


    // "morphTo" the "objectable" to the "objectable" row in "objects" table on Obj Model
    public function objectable() {
        return $this->morphTo();
    }
}
