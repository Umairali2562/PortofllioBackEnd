<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssigningAdminPermissionsToUsers
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
        $authenticatedUser = Auth::user();

        if ($authenticatedUser && !($authenticatedUser->roles()->where('slug', 'like', 'Administrator')->exists())) {

            // Check if 'Admin' or 'Administrator' role_slug is being assigned
            $roleIds = $request->input('role');

            // Ensure $roleIds is an array
            $roleIds = is_array($roleIds) ? $roleIds : [$roleIds];

            $roleSlugs = Role::whereIn('id', $roleIds)->pluck('slug')->toArray();

            foreach ($roleSlugs as $roleSlug) {
                if (preg_match("/\bAdmin\b|\bAdministrator\b/i", $roleSlug)) {
                    return redirect()->back()->with('error', 'You are not authorized to Assign Administrator role.');
                }
            }

            $permissionsArr = $request->input('permissions');

            // Check if $permissionsArr is not null
            if (!is_null($permissionsArr)) {
                // Get permission objects based on IDs
                $permissions = Permission::whereIn('id', $permissionsArr)->pluck('slug')->toArray();

                // Check if 'Admin' or 'Administrator' permissions are being assigned
                foreach ($permissions as $permission) {
                    if (preg_match("/\bAdmin\b|\bAdministrator\b/i", $permission)) {
                        return redirect()->back()->with('error', 'You are not authorized to Assign Administrator Permissions to any role.');
                    }
                }
            }
        }
        return $next($request);
    }
}
