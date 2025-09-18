<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRolesUpdatePermission
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


        $roleId = $request->route('role'); // Assuming your route parameter for the role is 'role'
        $authenticatedUser = Auth::user();
        // Check if any of the authenticated user's roles have the same ID as the role being updated
        foreach ($authenticatedUser->roles as $role) {

            if ($role->id == $roleId->id) {
                // Authenticated user is trying to update their own role
                return redirect()->back()->with('error', 'You cannot update your own role permissions.');
            }
        }

        return $next($request);

    }
}
