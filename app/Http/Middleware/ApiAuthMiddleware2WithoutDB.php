<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthMiddleware2WithoutDB
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

        $token = $request->bearerToken();

        if (!$token) {
            // Token is missing or invalid
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validate the token using Passport's built-in method
        if (!Auth::guard('api')->check()) {
            // Invalid token
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
