<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class AssignUserAdminPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        // Get all users with a role having slug "admin" or "administrator"
        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('slug', ['administrator'/*,'admin'*/]);
        })->get();

        foreach ($users as $user) {
            // Get all permissions
            $permissions = Permission::all();
            // Check if the user has the "admin" role
           /* $isAdmin = $user->roles->pluck('slug')->contains(function ($slug) {
                return preg_match("/^admin$/i", $slug);
            });*/

            // If the user has the "admin" role, exclude "manage_permissions" permission
           /* if ($isAdmin) {
                $permissions = $permissions->filter(function ($permission) {
                    return $permission->slug !== 'manage-permissions';
                });
                $user->permissions()->sync($permissions);
            }else{*/
                $user->permissions()->sync($permissions);
           // }

        }

        return $next($request);
    }
}
