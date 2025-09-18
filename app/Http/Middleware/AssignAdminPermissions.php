<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\Role;
use Closure;
use Illuminate\Http\Request;

class AssignAdminPermissions
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
        // Get all roles with slug "admin" or "administrator"
        $adminRoles = Role::where('slug', 'like', 'administrator')->get(); //->orWhere('slug', 'like', 'administrator')->get();

        foreach ($adminRoles as $adminRole) {
            // Get all permissions
            $permissions = Permission::all();

            /*// If the role has slug "admin", exclude "manage_permissions" permission
            if (($adminRole->slug === 'admin')||($adminRole->slug === 'Admin')) {
                $permissions = $permissions->filter(function ($permission) {
                    return $permission->slug !== 'manage-permissions';
                });
                $adminRole->permissions()->sync($permissions);
            } else {*/
                // Sync all permissions for roles other than "admin"
                $adminRole->permissions()->sync($permissions);
           // }
        }

        return $next($request);
    }
}
