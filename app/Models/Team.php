<?php

namespace App\Models;

use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'personal_team' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'personal_team',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];


    // Run this "booted" function when a new team is created.
    public static function booted() {
        // When this Team is created, create a root folder for a team
        static::created(function ($team) {
            // Access the "objects" relationship, and make in memory
            $object = $team->objects()->make(['parent_id' => null]);

            // Access the "objectable" poloymorphic relationship [Located in Obj model], then "associate" a newly created folder with the team name
            // -- In other words. this will create a row in the "folders" table with the team `name`, `uuid` and `team_id`
            $object->objectable()->associate($team->folders()->create(['name' => $team->name]));

            // Save the "object" to database
            $object->save();
        });
    }


    // A Team has many "Objects" -- relating to Model[Object]
    public function objects() {
        return $this->hasMany(Obj::class);
    }

     // A Team has many "Folders" -- relating to Model[Folder]
     public function folders() {
        return $this->hasMany(Folder::class);
    }

     // A Team has many "Files" -- relating to Model[File]
     public function files() {
        return $this->hasMany(File::class);
    }
}
