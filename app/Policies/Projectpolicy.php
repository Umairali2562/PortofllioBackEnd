<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Projectpolicy
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

    public function Create_Projects(User $user){

        return $user->hasPermission('create-projects');

    }
    public function View_Projects(User $user){

        return $user->hasPermission('view-projects');

    }
    public function Update_Projects(User $user){

        return $user->hasPermission('update-projects');

    }
    public function Delete_Projects(User $user){

        return $user->hasPermission('delete-projects');
    }
}
