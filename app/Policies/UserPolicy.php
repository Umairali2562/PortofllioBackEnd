<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

    public function View_Users(User $user){

        return $user->hasPermission('view-users');

    }
    public function viewUsers(User $user)
    {
        dd('viewUsers method called');
        return $user->hasPermission('view-users');
    }

    public function Create_Users(User $user){
        return $user->hasPermission('create-users');
    }
    public function Update_Users(User $user){
        return $user->hasPermission('update-users');
    }
    public function Delete_Users(User $user){
        return $user->hasPermission('delete-users');
    }
}
