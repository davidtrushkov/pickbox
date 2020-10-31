<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function download(User $user, File $file) {
        // Make sure current user team matches file they are trying to download
        return $file->team_id === $user->currentTeam->id;
    }
}
