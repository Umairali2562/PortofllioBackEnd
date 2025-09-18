<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestimonialPolicy
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

    public function Create_Testimonials(User $user){

        return $user->hasPermission('create-testimonials');

    }
    public function View_Testimonials(User $user){

        return $user->hasPermission('view-testimonials');

    }
    public function Update_Testimonials(User $user){

        return $user->hasPermission('update-testimonials');

    }
    public function Delete_Testimonials(User $user){

        return $user->hasPermission('delete-testimonials');
    }
}
