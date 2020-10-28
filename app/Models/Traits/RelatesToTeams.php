<?php
namespace App\Models\Traits;

trait RelatesToTeams {

    // Get the current users team ID
    public function scopeForCurrentTeam($query) {
        $query->where('team_id', auth()->user()->currentTeam->id);
    }
    
}