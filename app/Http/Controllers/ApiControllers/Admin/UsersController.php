<?php

namespace App\Http\Controllers\ApiControllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware(['check.user.update.permission', 'Administrator.role.update.blocked'])->only(['update']); //changing your own permissions
        //  $this->middleware('block.normal.user.from.Assigning.admin.permissions.users')->only(['store','update']);
        $this->middleware('Administrator.User.cannot.be.deleted')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $this->authorize('view_users', User::class);
            $users = User::with('roles','permissions')->orderBy('id')->get();
          //  $users = User::with('permissions')->orderBy('id')->get();

            return response()->json([
                'users' => $users,
            ], 200);
        }catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            if ($request->has('role_id')) {

                $roles = Role::where('id', $request->role_id)->first();

                $permissions = $roles->permissions;

                return $permissions;
            }

            $roles = Role::all();

            return response()->json([
                'Roles' => $roles,
            ], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('Create_Users', User::class);

        try {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            if ($request->filled('role')) {
                $user->roles()->attach([$request->role]);
            }

            if ($request->filled('permissions')) {
                $user->permissions()->attach($request->permissions);
            }

            return response()->json([
                'Success' => 'success',
            ], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('admin.users.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::get();  // Fetch all roles

        $userRole = $user->roles->first();  // Get the first role assigned to the user, if any

        if ($userRole != null) {
            $rolePermissions = $userRole->allRolePermissions;  // Get all permissions associated with the user's role
        } else {
            $rolePermissions = null;
        }

        $userPermissions = $user->permissions;  // Get all permissions directly assigned to the user

        return [
            'user' => $user,
            'roles' => $roles,
            'userRole' => $userRole,
            'rolePermissions' => $rolePermissions,
            'userPermissions' => $userPermissions
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('Update_Users', User::class);

        // Update user information
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password if provided
        if ($request->password !== null) {
            $user->password = Hash::make($request->password);
        }

        // Save user changes
        $user->save();

        // Detach all existing roles and permissions
        $user->roles()->detach();
        $user->permissions()->detach();

        // Attach the requested role if it exists
        if ($request->role !== null) {
            $requestedRole = Role::find($request->role);

            if ($requestedRole !== null) {
                // Attach the role if it exists
                $user->roles()->attach($requestedRole->id);

                // Attach only the permissions that exist in the role
                $requestedPermissions = [];

                if ($request->permissions !== null && $requestedRole !== null) {
                    $requestPermissionsArray = json_decode($request->permissions, true);

                    $requestedPermissions = array_intersect(
                        $requestPermissionsArray,
                        $requestedRole->permissions->pluck('id')->toArray()
                    );
                }

                foreach ($requestedPermissions as $permission) {
                    $user->permissions()->attach($permission);
                }

                $user->save();
            }
        }

        return "success";
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->authorize('Delete_Users', User::class);
            $user = User::find($id);
            $user->roles()->detach();
            $user->permissions()->detach();
            $user->delete();
            return response()->json(['message' => 'The User has been deleted successfully'], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            // Handle 401 response for unauthorized access
            return response()->json(['message' => 'Unauthorized'], 401);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'Internal Server Error'], 500);
        }

    }

}
