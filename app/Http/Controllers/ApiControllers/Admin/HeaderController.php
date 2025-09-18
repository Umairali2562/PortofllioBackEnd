<?php

namespace App\Http\Controllers\ApiControllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Header;
use Illuminate\Http\Request;

class HeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $headers=Header::all();
        return $headers;
    }

    public function view()
    {
        $this->authorize('View_Headers', Header::class);
        $headers=Header::all();
        return $headers;
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
        $this->authorize('Create_Headers', Header::class);
        try {
            // Check if a record already exists
            $existingHeader = Header::first();

            // Log the existing header for debugging
            \Log::info('Existing Header:', ['header' => $existingHeader]);

            // If a record exists, update it instead of creating a new one
            if ($existingHeader) {
                // Process the file uploads and store them in the public disk
                if ($request->hasFile('MainImage')) {
                    $mainImagePath = $request->file('MainImage')->store('public/images');
                    $existingHeader->MainImage = str_replace('public/', '', $mainImagePath);
                }

                if ($request->hasFile('cv')) {
                    $cvPath = $request->file('cv')->store('public/cv');
                    $existingHeader->cv = str_replace('public/', '', $cvPath);
                }

                // Update other fields
                $existingHeader->update($request->only(['Headings', 'Description']));

                return response()->json(['message' => 'Header updated successfully'], 200);
            }

            // Validate the incoming request
            $validatedData = $request->validate([
                'Headings' => 'required|string',
                'Description' => 'required|string',
                'MainImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            ]);

            // Process the file uploads and store them in the public disk
            $mainImagePath = $request->file('MainImage')->store('public/images');
            $cvPath = $request->file('cv')->store('public/cv');

            // Get the relative paths for storing in the database
            $mainImagePathRelative = str_replace('public/', '', $mainImagePath);
            $cvPathRelative = str_replace('public/', '', $cvPath);

            // Create a new Header instance
            $header = new Header();
            $header->Headings = $validatedData['Headings'];
            $header->Description = $validatedData['Description'];
            $header->MainImage = $mainImagePathRelative; // Store relative path
            $header->cv = $cvPathRelative; // Store relative path
            $header->save();

            return response()->json(['message' => 'Header created successfully'], 201);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error in store method: ' . $e->getMessage());

            // Return an error response
            return response()->json(['message' => 'Error occurred while creating/updating header'], 500);
        }
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Header  $header
     * @return \Illuminate\Http\Response
     */
    public function show(Header $header)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Header  $header
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('Update_Headers', Header::class);
        $headers=Header::find($id);
        return $headers;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Header  $header
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('Update_Headers', Header::class);


        $header = Header::find($id);
        if (!$header) {
            return response()->json(['message' => 'Header not found'], 404);
        }

        // Validate the incoming request
        $validatedData = $request->validate([
            'Headings' => 'string',
            'Description' => 'string',
            'MainImage' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the max file size as needed
            'cv' => 'file|mimes:pdf,doc,docx|max:2048', // Adjust the accepted file types and max size as needed
        ]);

        // Update the fields only if they are provided in the request
        if ($request->has('Headings')) {
            $header->Headings = $validatedData['Headings'];
        }
        if ($request->has('Description')) {
            $header->Description = $validatedData['Description'];
        }
        if ($request->hasFile('MainImage')) {
            $mainImagePath = $request->file('MainImage')->store('public/images');
            $mainImagePathRelative = str_replace('public/', '', $mainImagePath);
            $header->MainImage = $mainImagePathRelative;
        }
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('public/cv');
            $cvPathRelative = str_replace('public/', '', $cvPath);
            $header->cv = $cvPathRelative;
        }

        $header->save();

        return response()->json(['message' => 'Header updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Header  $header
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('Delete_Headers', Header::class);
        try {

            $narbar = Header::find($id);
            $narbar->delete();
            return response()->json(['message' => 'The Headers has been deleted successfully'], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            // Handle 401 response for unauthorized access
            return response()->json(['message' => 'Unauthorized'], 401);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
