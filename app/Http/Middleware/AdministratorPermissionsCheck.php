<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\Role;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdministratorPermissionsCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
           /* $user = Auth::user();
            $Roleid = $user->roles->pluck('id')->first();
            $Role = $user->roles->first();

            if ($Roleid) {

                $user_slug = Role::find($Roleid)->pluck('slug');

                // Filter the collection to get only the "administrator" role
                $administratorRole = $user_slug->filter(function ($role) {
                    return strtolower($role) === 'administrator';
                });

                $role_slug=$administratorRole->first();

                if (($role_slug == "Administrator") || ($role_slug == "Admin")|| ($role_slug == "administrator") || ($role_slug == "admin")) {
                    $allPermissions = Permission::pluck('id')->toArray();

                    $Role->permissions()->sync($allPermissions);
                }
            }

            // for user's permissions
            if ($user && ($user->hasRole('Administrator') || $user->hasRole('Admin')|| $user->hasRole('administrator')|| $user->hasRole('admin')) ) {
                // Assign all permissions to the user
                $allPermissions = Permission::pluck('id')->toArray();
                $user->permissions()->sync($allPermissions);
            }*/

            return $next($request);
        }


        return redirect('/login');
    }
}
