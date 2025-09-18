<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HeaderPolicy
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

    public function Create_Headers(User $user){

        return $user->hasPermission('create-header');

    }
    public function View_Headers(User $user){

        return $user->hasPermission('view-header');

    }
    public function Update_Headers(User $user){

        return $user->hasPermission('update-header');

    }

    public function Delete_Headers(User $user){

        return $user->hasPermission('delete-header');
    }
}
