<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserUpdatePermission
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
        $userId = $request->route('user'); // Assuming your route parameter for the user is 'user'
        $authenticatedUser = Auth::user();

        // Check if the authenticated user is trying to update their own role or permissions
        if ($authenticatedUser['id'] == $userId->id) {

            // Check if role or permissions fields are present in the request
            if ($request->filled('role') || $request->filled('permissions')) {
                return redirect('/users')->with('error', 'You cannot update your own role or permissions.');
            }
        }

        return $next($request);
    }
}
