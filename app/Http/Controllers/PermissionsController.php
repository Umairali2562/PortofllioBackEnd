<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Using gate
      /*  if (Gate::allows('manage_permissions')) {
            // User is allowed to manage roles
            $Permissions=Permission::orderBy('id')->get();
            return view('admin.Permissions.index',compact('Permissions'));
        }*/

        $this->authorize('view_permissions', Permission::class);


        // User is allowed to manage roles
        $Permissions=Permission::orderBy('id')->get();
        return view('admin.Permissions.index',compact('Permissions'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.Permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create_permissions',Permission::class);
        if($request->input('Permissions')){

            $listOfPermissions = explode(',', $request->input('Permissions'));//create array from separated/coma permissions

            foreach ($listOfPermissions as $permission) {
                $permissions = new Permission();
                $permissions->name = $permission;
                $permissions->slug = strtolower(str_replace(" ", "-", $permission));
                $permissions->save();

            }

            return redirect('/Permissions')->with('success','The Permission Was Created Successfully');



        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return "hi";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $Permisisons=Permission::find($id);
        return view('admin.Permissions.edit',compact('Permisisons'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->authorize('update_permissions',Permission::class);
        if ($request->input('Permissions')) {
            $permissions = Permission::find($id);
            $permissions->update(['name' => $request->input('Permissions')]);
            $slug = strtolower(str_replace(" ", "-", $request->input('Permissions')));
            $permissions->update(['slug' => $slug]);
            return redirect('/Permissions')->with('success','The Permission Has Been Updated Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete_permissions',Permission::class);
        $Permissions=Permission::find($id);
        //$Permissions->roles()->delete();
        $Permissions->roles()->detach();
        $Permissions->delete();
        return redirect('/Permissions')->with('success', 'The Permission Has Been Deleted Successfully..');
    }

}
