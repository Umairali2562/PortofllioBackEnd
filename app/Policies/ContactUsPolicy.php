<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactUsPolicy
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

    public function View_ContactUs(User $user){

        return $user->hasPermission('view-contactus');

    }
    public function Delete_ContactUs(User $user){

        return $user->hasPermission('delete-contactus');
    }


}
