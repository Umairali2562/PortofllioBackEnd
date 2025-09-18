<?php

namespace App\Http\Controllers\ApiControllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Navbar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NavbarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $navbar=Navbar::all();
        return $navbar;
    }
    public function view()
    {
        $this->authorize('View_Navbar', Navbar::class);
        $navbar=Navbar::all();
        return $navbar;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('Create_Navbar', Navbar::class);
      $NavbarName=$request->input('NavbarName');
      $NavbarLink=$request->input('NavbarLink');

        $navbar=new Navbar();
        $navbar->title=$NavbarName;
        $navbar->link=$NavbarLink;
        $navbar->save();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('Update_Navbar', Navbar::class);
        $navbar=Navbar::find($id);
       return $navbar;
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
        $this->authorize('Update_Navbar', Navbar::class);
        $NavbarName=$request->input('NavbarName');
        $NavbarLink=$request->input('NavbarLink');
        $navbar=Navbar::find($id);
        $navbar->title=$NavbarName;
        $navbar->link=$NavbarLink;
        $navbar->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $this->authorize('Delete_Navbar', Navbar::class);

        try {

            $narbar = Navbar::find($id);
            $narbar->delete();
            return response()->json(['message' => 'The Navlink has been deleted successfully'], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            // Handle 401 response for unauthorized access
            return response()->json(['message' => 'Unauthorized'], 401);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
