<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckDeleteUserPermissions
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
        $userIdToDelete = $request->route('id');

        // Check if the user to delete has a role with the slug 'administrator' or 'admin'
        $userToDelete = User::find($userIdToDelete);


        if ($userToDelete && $userToDelete->roles()->where('slug', 'like', 'Administrator')->exists() || $userToDelete->roles()->where('slug', 'like', 'admin')->exists()) {

            // Check if the authenticated user is an 'Administrator'
            if ($authenticatedUser->roles()->where('slug', 'like', 'Administrator')->exists() && $userIdToDelete != $authenticatedUser->id) {

                return $next($request);
            } else {
                return redirect()->back()->with('error', 'You are not authorized to delete a user with Administrator or admin role.');
            }
        }

        return $next($request);
    }
}
