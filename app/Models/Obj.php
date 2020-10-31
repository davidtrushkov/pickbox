<?php

namespace App\Models;

use App\Models\Traits\RelatesToTeams;
use Laravel\Scout\Searchable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Obj extends Model
{
    use HasFactory, Searchable, RelatesToTeams, HasRecursiveRelationships;

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

        static::deleting(function ($model) {
            // Delete objectable
            optional($model->objectable)->delete();

            // Delete decendants
            $model->descendants->each->delete();
        });
    }

    // "morphTo" the "objectable" to the "objectable" row in "objects" table on Obj Model
    public function objectable() {
        return $this->morphTo();
    }


    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'team_id' => $this->team_id,
            'name' => $this->objectable->name,
            'path' => $this->ancestorsAndSelf->pluck('objectable.name')->reverse()->join('/')
        ];
    }

}
