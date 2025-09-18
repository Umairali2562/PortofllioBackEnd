<?php

namespace App\Http\Controllers\ApiControllers\Admin;

use App\Http\Controllers\Controller;
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


        $this->authorize('view_permissions', Permission::class);

        $Permissions=Permission::orderBy('id')->get();
        return $Permissions;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

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

           return "Permissions added";
        }else{
            return "failed, did you left the permssion empty?";
    }
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id){
       $Permission=Permission::find($id);
        return $Permission;
    }


    public function update(Request $request, $id)
    {


        $this->authorize('update_permissions',Permission::class);
        if ($request->input('Permissions')) {
            $permissions = Permission::find($id);
            $permissions->update(['name' => $request->input('Permissions')]);
            $slug = strtolower(str_replace(" ", "-", $request->input('Permissions')));
            $permissions->update(['slug' => $slug]);
            return "Success Permissions Updated..";
        }else{
            return "Failed";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
// In your API controller
    public function destroy($id)
    {
        try {
            $this->authorize('delete_permissions', Permission::class);
            $permission = Permission::find($id);

            if (!$permission) {
                return response()->json(['error' => 'Permission not found'], 404);
            }

            $permission->roles()->detach();
            $permission->delete();

            return response()->json(['message' => 'The Permission has been deleted successfully'],200);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error("Error during permission deletion: " . $e->getMessage());

            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


}
