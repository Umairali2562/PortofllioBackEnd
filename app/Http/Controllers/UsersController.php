<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware(['check.user.update.permission','Administrator.role.update.blocked'])->only(['update']); //changing your own permissions
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
        $this->authorize('View_Users',User::class);
        $users = User::orderBy('id')->get();
        return view('admin.Users.index', compact('users'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->ajax()){
            $roles = Role::where('id', $request->role_id)->first();
            $permissions = $roles->permissions;

            return $permissions;
        }

        $roles = Role::all();

        return view('admin.users.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('Create_Users',User::class);
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        if ($request->filled('role')) {

            $user->roles()->attach($request->role);
        }

        if ($request->filled('permissions')) {
            foreach ($request->permissions as $permission) {
                $user->permissions()->attach($permission);
            }
        }

        return redirect('/users')->with('success','The User Has Been Created Successfully');


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
        $roles = Role::get();
        $userRole = $user->roles->first();
        if($userRole != null){
            $rolePermissions = $userRole->allRolePermissions;
        }else{
            $rolePermissions = null;
        }
        $userPermissions = $user->permissions;


        return view('admin.users.edit', [
            'user'=>$user,
            'roles'=>$roles,
            'userRole'=>$userRole,
            'rolePermissions'=>$rolePermissions,
            'userPermissions'=>$userPermissions
        ]);
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
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password != null) {
            $user->password = Hash::make($request->password);
        }

        $userid = $user->id;
        $userRole = $user->roles->first();

        $user->save();

        // Detach all existing roles and permissions
        $user->roles()->detach();
        $user->permissions()->detach();

        // Attach the requested role if it exists
        if ($request->role != null) {
            $requestedRole = Role::find($request->role);

            if ($requestedRole != null) {
                // Attach the role if it exists
                $user->roles()->attach($requestedRole->id);

                // Attach only the permissions that exist in the role
                $requestedPermissions = [];

                if ($request->permissions != null && $requestedRole != null) {
                    $requestedPermissions = array_intersect(
                        $request->permissions,
                        $requestedRole->permissions->pluck('id')->toArray()
                    );
                }

                foreach ($requestedPermissions as $permission) {
                    $user->permissions()->attach($permission);
                }

                $user->save();
            }
        }

        return redirect('/users')->with('success', 'The User Has Been Updated Successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('Delete_Users',User::class);
       $user=User::find($id);
        $user->roles()->detach();
        $user->permissions()->detach();
        $user->delete();
        return redirect('/users')->with('success','The User Has Been Successfully Deleted...');
    }

}
