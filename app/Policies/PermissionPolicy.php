<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
    public function view_permissions(User $user)
    {
        return $user->hasPermission('view-permissions');
    }
    public function create_permissions(User $user)
    {
        return $user->hasPermission('create-permissions');
    }

    public function update_permissions(User $user){
        return $user->hasPermission('update-permissions');
    }

    public function delete_permissions(User $user){
        return $user->hasPermission('delete-permissions');
    }


}
