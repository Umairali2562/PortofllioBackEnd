<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\ContactUS;
use App\Models\Header;
use App\Models\Navbar;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Role;
use App\Models\Testimonials;
use App\Models\User;
use App\Policies\ContactUsPolicy;
use App\Policies\HeaderPolicy;
use App\Policies\NavbarPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\Projectpolicy;
use App\Policies\RolesPolicy;
use App\Policies\TestimonialPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Permission::class => PermissionPolicy::class,
        Role::class=>RolesPolicy::class,
        User::class=>UserPolicy::class,
        Navbar::class=>NavbarPolicy::class,
        Header::class=>HeaderPolicy::class,
        Project::class=>ProjectPolicy::class,
        Testimonials::class=>TestimonialPolicy::class,
        ContactUS::class=>ContactUsPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //these gates are for views ...


        //for permissions view :
        Gate::define('view_permissions_gate', function ($user) {
            return $user->hasPermission('view-permissions');
        });

        Gate::define('create_permissions_gate', function ($user) {
            return $user->hasPermission('create-permissions');
        });

        Gate::define('update_permissions_gate', function ($user) {
            return $user->hasPermission('update-permissions');
        });

        Gate::define('delete_permissions_gate', function ($user) {
            return $user->hasPermission('delete-permissions');
        });


        //for roles views :
        Gate::define('view_roles_gate', function ($user) {
            return $user->hasPermission('view-roles');
        });

        Gate::define('create_roles_gate', function ($user) {
            return $user->hasPermission('create-roles');
        });

        Gate::define('update_roles_gate', function ($user) {
            return $user->hasPermission('update-roles');
        });

        Gate::define('delete_roles_gate', function ($user) {
            return $user->hasPermission('delete-roles');
        });



        //for user views :
        Gate::define('view_users_gate', function ($user) {
            return $user->hasPermission('view-users');
        });

        Gate::define('create_users_gate', function ($user) {
            return $user->hasPermission('create-users');
        });

        Gate::define('update_users_gate', function ($user) {
            return $user->hasPermission('update-users');
        });

        Gate::define('delete_users_gate', function ($user) {
            return $user->hasPermission('delete-users');
        });


        //Gates for Project
        Gate::define('create_projects_gate', function ($user) {
            return $user->hasPermission('create-projects');
        });
        Gate::define('view_projects_gate', function ($user) {
            return $user->hasPermission('view-projects');
        });
        Gate::define('delete_projects_gate', function ($user) {
            return $user->hasPermission('delete-projects');
        });

        Gate::define('update_projects_gate', function ($user) {
            return $user->hasPermission('update-projects');
        });

           //Gates for Navbar
        Gate::define('create_navbar_gate', function ($user) {
            return $user->hasPermission('create-navbar');
        });
        Gate::define('view_navbar_gate', function ($user) {
            return $user->hasPermission('view-navbar');
        });
        Gate::define('delete_navbar_gate', function ($user) {
            return $user->hasPermission('delete-navbar');
        });

        Gate::define('update_navbar_gate', function ($user) {
            return $user->hasPermission('update-navbar');
        });



        //Gates for headers
        Gate::define('create_header_gate', function ($user) {
            return $user->hasPermission('create-header');
        });
        Gate::define('view_header_gate', function ($user) {
            return $user->hasPermission('view-header');
        });
        Gate::define('delete_header_gate', function ($user) {
            return $user->hasPermission('delete-header');
        });

        Gate::define('update_header_gate', function ($user) {
            return $user->hasPermission('update-header');
        });


        //Gates for Testimonials
        Gate::define('create_testimonial_gate', function ($user) {
            return $user->hasPermission('create-testimonials');
        });
        Gate::define('view_testimonial_gate', function ($user) {
            return $user->hasPermission('view-testimonials');
        });
        Gate::define('delete_testimonial_gate', function ($user) {
            return $user->hasPermission('delete-testimonials');
        });

        Gate::define('update_testimonial_gate', function ($user) {
            return $user->hasPermission('update-testimonials');
        });


        //contact us :
        Gate::define('view_contactus_gate', function ($user) {
            return $user->hasPermission('view-contactus');
        });
        Gate::define('delete_contactus_gate', function ($user) {
            return $user->hasPermission('delete-contactus');
        });

    }
}
