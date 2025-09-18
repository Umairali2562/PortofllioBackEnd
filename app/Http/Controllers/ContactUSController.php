<?php

namespace App\Http\Controllers;

use App\Events\NotifyProcessed;
use App\Models\ContactUS;
use Illuminate\Http\Request;

class ContactUSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('View_ContactUs', ContactUS::class);
        return $contactUS=ContactUS::all();
    }


public function indexall()
{
    try {
        // Fetch all notifications
        $notifications = ContactUS::all();
        // Return the notifications as JSON response
        return response()->json($notifications);
    } catch (\Exception $e) {
        // Handle any errors
        return response()->json(['error' => 'Failed to fetch notifications'], 500);
    }
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // Log the event trigger
        \Log::info('Event trigger: NotifyProcessed');

        // Validate the incoming request data
        $validatedData = $request->validate([
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'Email' => 'required|email|max:255',
            'Phone' => 'required|string|max:255',
            'Msg' => 'required|string',
        ]);

        try {
            // Log the validated data
            \Log::info('Validated data:', $validatedData);

            // Create a new Contact model instance and fill it with the validated data
            $contact = new ContactUS();
            $contact->FirstName = $validatedData['FirstName'];
            $contact->LastName = $validatedData['LastName'];
            $contact->Email = $validatedData['Email'];
            $contact->Phone = $validatedData['Phone'];
            $contact->Msg = $validatedData['Msg'];

            // Save the contact to the database
            $contact->save();

            event(new NotifyProcessed($contact));

            // Optionally, you can return a success response
            return response()->json(['message' => 'Contact saved successfully'], 201);
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error saving contact:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            // Handle any exceptions or errors
            return response()->json(['error' => 'Failed to save contact'], 500);
        }
    }

    public function markAsRead(Request $request)
    {
        $ids = $request->input('ids');
        ContactUS::whereIn('id', $ids)->update(['read' => true]);
        return response()->json(['message' => 'Notifications marked as read'], 200);
    }

    public function notify(Request $request)
    {
        $notification = ContactUS::create([
            'message' => $request->input('message'),
        ]);
        return response()->json($notification, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContactUS  $contactUS
     * @return \Illuminate\Http\Response
     */
    public function show(ContactUS $contactUS)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContactUS  $contactUS
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactUS $contactUS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContactUS  $contactUS
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContactUS $contactUS)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactUS  $contactUS
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('Delete_ContactUs', ContactUS::class);
        try {

            $contactus=\App\Models\ContactUS::find($id);
            $contactus->delete();
            return response()->json(['message' => 'The Contact Message has been deleted successfully'], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            // Handle 401 response for unauthorized access
            return response()->json(['message' => 'Unauthorized'], 401);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
