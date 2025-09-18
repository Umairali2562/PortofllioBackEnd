<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ValidateRoleSlug
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
        $roleSlug = $request->input('role_slug');

        // Check if the user is an admin
        $isAdmin = Auth::check() && Auth::user()->isAdmin();

        // Validate the role slug
        if ($roleSlug !== null && Str::contains($roleSlug, 'admin','administrator') && !$isAdmin) {
            return redirect('/roles')->with('error', 'You are not allowed to use Admin in the slug');
        }

        // Check if a role with the same name already exists
        if (Role::where('name', $request->role_name)->exists()) {
            return redirect('/roles')->with('error', 'Role with this name already exists');
        }

        return $next($request);
    }
}
