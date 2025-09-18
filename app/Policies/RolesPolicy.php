<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolesPolicy
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

    public function View_Roles(User $user){

        return $user->hasPermission('view-roles');
    }
    public function Create_Roles(User $user){
        return $user->hasPermission('create-roles');
    }
    public function Update_Roles(User $user){
        return $user->hasPermission('update-roles');
    }
    public function Delete_Roles(User $user){
        return $user->hasPermission('delete-roles');
    }

}
