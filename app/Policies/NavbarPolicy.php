<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NavbarPolicy
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

    public function Create_Navbar(User $user){

        return $user->hasPermission('create-navbar');

    }
    public function View_Navbar(User $user){

        return $user->hasPermission('view-navbar');

    }
    public function Update_Navbar(User $user){

        return $user->hasPermission('update-navbar');

    }
    public function Delete_Navbar(User $user){

        return $user->hasPermission('delete-navbar');
    }
}
