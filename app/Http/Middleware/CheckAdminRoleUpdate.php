<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminRoleUpdate
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
        $authenticatedUser=Auth::user();

        $user_to_be_updated=$request->route('user');

    if($user_to_be_updated->roles->first()){
        $role_of_user_to_be_updated=$user_to_be_updated->roles->first();


        $slug_of_authenticated_user=$authenticatedUser->roles->first();
        //dd($slug_of_authenticated_user->slug);

            $role_id_of_user_to_be_updated = $role_of_user_to_be_updated->id;


            $slug_of_User_to_be_Updated = Role::find($role_id_of_user_to_be_updated)->slug;
            $auth_slug = $slug_of_authenticated_user->slug;

            if (preg_match("/\b(admin|administrator)\b/i", $slug_of_User_to_be_Updated)) {

                if (preg_match("/\b(administrator)\b/i", $auth_slug)) {
                    return $next($request);
                } else {
                    return redirect()->back()->with('error', 'You are not authorized to update admin or administrator roles');
                }
            }
        }

        return $next($request);
    }
}
