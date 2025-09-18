<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials)) {
            // Assuming you have a 'api_tokens' table with a 'token' column
           // $token = $this->generateApiToken(auth()->user()->id);
            $user = Auth::user();

            $token = $user->createToken('AppName')->accessToken;


            return response()->json(['Bearer' => $token], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();

        if ($user) {
            //$user->token()->revoke();
            $user->token()->delete();

            return response()->json(['message' => 'Successfully logged out'], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function Approval()
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user_permissions = $user->permissions;
        $role = $user->roles->first();
        $role_id = $role->id;
        $UserRole = Role::find($role_id);
        $slug=$UserRole->slug;
        $role_Permissions = $UserRole->permissions;

        return response()->json([
            'message' => 'Valid Bearer Token',
            'user' => $user->name,
            'user_permissions' => $user_permissions,
            'user_role'=>$UserRole,
            'role_slug'=>$slug,
            'role_permissions' => $role_Permissions,
        ], 200);
    }


   /* public function approval()
    {
        return response()->json(['message' => 'Valid Bearer token. You can proceed.']);
    }

    // Custom method to generate and store API token
    private function generateApiToken($userId)
    {
        $token = bin2hex(random_bytes(32)); // Generate a random token (adjust the length if needed)

        // Store the token in the 'api_tokens' table
        \DB::table('api_tokens')->insert([

            'token' => $token,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $token;
    }*/



}
