<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthMiddleware
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
        $token = $request->bearerToken();

        if ($token && $this->isValidToken($token)) {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    private function isValidToken($token)
    {
        // Implement your token validation logic here
        // Example: Check against a users table or an API token storage

        // For simplicity, let's assume you have a 'api_tokens' table with a 'token' column
        return \DB::table('api_tokens')->where('token', $token)->exists();
    }

}
