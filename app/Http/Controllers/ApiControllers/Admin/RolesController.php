<?php
//hi
namespace App\Http\Controllers\ApiControllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.role.update.permission')->only(['update']); //changing your own role
       // $this->middleware('block.normal.user.from.Assigning.admin.permissions.roles')->only(['store','update']);//this will block the normal users to assign admin permissions to any role
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function index()
    {
        $this->authorize('View_Roles',Role::class);
        $roles = Role::with('permissions')->orderBy('id')->get();
        return $roles;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

       $Permissions=Permission::all();

       return $Permissions;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('Create_Roles',Role::class);

        $role = new Role();
        $role->name = ucwords($request->role_name);
        $role->slug = ucwords($request->role_slug);
        $role->save();

        $permissionsArr = $request->input('roles_permissions');



            // Use sync to synchronize the role's permissions
            $role->permissions()->sync($permissionsArr);
            return "Success";
        //return redirect('/roles')->with('success', 'The Role Has Been Created Successfully..');
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return view('admin.roles.show', ['role' => $role]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {

        try {
            $rolePermissions = $role->permissions;
            $Permissions = Permission::all();


            return response()->json([
                'RolesPermissions' => $rolePermissions,
                'Permissions' => $Permissions,
                'Role'=>$role,
            ], 200);
        }catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {

        $this->authorize('Update_Roles',Role::class);

        // Update role details
        $role->name = ucwords($request->role_name);
        $role->slug = ucwords($request->role_slug);
        $role->save();

        // Get updated permissions array from the request
        $permissionsArr = $request->input('roles_permissions') ?? [];

        // Detach removed permissions from the role
        $removedPermissions = array_diff($role->permissions->pluck('id')->toArray(), $permissionsArr);
        $role->permissions()->detach($removedPermissions);

        // Remove the same permissions from users who have this role
        foreach ($role->users as $user) {
            $user->permissions()->detach($removedPermissions);
        }

        // Use sync to synchronize the role's permissions
        $role->permissions()->sync($permissionsArr);

        // Sync permissions for each user associated with the role
        foreach ($role->users as $user) {
            // Sync permissions only if the role's permissions are not null
            if ($permissionsArr !== null) {
                // Detach removed permissions from the user
                $user->permissions()->detach(array_diff($user->permissions->pluck('id')->toArray(), $permissionsArr));

                // Sync the role's permissions with the user, only for existing permissions
                $user->permissions()->syncWithoutDetaching(array_intersect($permissionsArr, $role->permissions->pluck('id')->toArray()));
            }
        }
        return redirect('/roles')->with('success','The Role Has Been Updated Successfully..');

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->authorize('Delete_Roles', Role::class);

            // Find the role
            $role = Role::find($id);

            // Check if the role exists
            if (!$role) {
                return response()->json(['message' => 'Role not found'], 404);
            }

            // Get the users associated with the role
            $users = $role->users;

            // Detach the role from users
            $role->users()->detach();

            // Delete the role
            $role->delete();

            // Detach permissions from each user associated with the role
            foreach ($users as $user) {
                $user->permissions()->detach();
            }

            return response()->json(['message' => 'The Permission has been deleted successfully'], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            // Handle 401 response for unauthorized access
            return response()->json(['message' => 'Unauthorized'], 401);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }



}
