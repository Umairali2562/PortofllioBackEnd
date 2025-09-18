<?php
namespace App\Http\Controllers\ApiControllers;
use App\Http\Controllers\Controller;
use App\Models\Testimonials as TestimonialsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Testimonials extends Controller
{

    function index(){
     $Testimonials=TestimonialsModel::all();
     return($Testimonials);
    }
    function view(){
        $this->authorize('View_Testimonials', TestimonialsModel::class);
     $Testimonials=TestimonialsModel::all();
     return($Testimonials);
    }


    function store(Request $request)
    {
        $this->authorize('Create_Testimonials', TestimonialsModel::class);

        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'Description' => 'required|string',
            'MainImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the max file size as needed
            'position' => 'required|string',
        ]);

        // Store the uploaded image
        $imagePath = $request->file('MainImage')->store('public/images');
        $imagePathRelative = str_replace('public/', '', $imagePath);

        // Fetch the base URL from the .env file
        $baseUrl = env('IMAGE_BASE_URL');

        // Concatenate the base URL with the image path relative to the storage
        $absoluteImageUrl = $baseUrl . '/' . $imagePathRelative;

        // Create a new testimonial instance
        $testimonial = new TestimonialsModel();
        $testimonial->name = $validatedData['name'];
        $testimonial->reviews = $validatedData['Description'];
        $testimonial->image_url = $absoluteImageUrl; // Save the absolute URL to the image
        $testimonial->job_titles = $validatedData['position'];
        $testimonial->save();

        // Optionally, you might return the created testimonial as a response
        return response()->json(['message' => 'Testimonial created successfully', 'testimonial' => $testimonial], 201);
    }




    public function edit($id)
    {
        $this->authorize('Update_Testimonials', TestimonialsModel::class);
        try {
            $testimonial = TestimonialsModel::find($id);
            if (!$testimonial) {
                return response()->json(['message' => 'Testimonial not found'], 404);
            }
            return response()->json($testimonial, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $this->authorize('Update_Testimonials', TestimonialsModel::class);
        try {
            $testimonial = TestimonialsModel::find($id);
            if (!$testimonial) {
                return response()->json(['message' => 'Testimonial not found'], 404);
            }

            // Define validation rules
            $rules = [
                'name' => 'string',
                'position' => 'string',
                'Description' => 'string',
                'MainImage' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the max file size as needed
            ];

            // If MainImage is not present in the request, make it optional
            if (!$request->hasFile('MainImage')) {
                unset($rules['MainImage']);
            }

            // Validate the incoming request data
            $validatedData = $request->validate($rules);

            // Update the testimonial fields
            $testimonial->name = $validatedData['name'];
            $testimonial->reviews = $validatedData['Description'];
            $testimonial->job_titles = $validatedData['position'];

            if ($request->hasFile('MainImage')) {
                // Store the new image in the storage directory
                $imagePath = $request->file('MainImage')->store('public/images');
                // Get the relative image path
                $imagePathRelative = str_replace('public/', '', $imagePath);
                // Generate the full URL using the storage path
                $fullImageUrl = url(Storage::url($imagePathRelative));
                $testimonial->image_url = $fullImageUrl;
            } // No else block needed because if no image is sent, it retains the previous image URL

            $testimonial->save();

            return response()->json(['message' => 'Testimonial updated successfully', 'testimonial' => $testimonial], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }


    public function destroy($id)
    {
        $this->authorize('Delete_Testimonials', TestimonialsModel::class);
        try {

           $testimonials=\App\Models\Testimonials::find($id);
            $testimonials->delete();
            return response()->json(['message' => 'The Testimonial has been deleted successfully'], 200);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            // Handle 401 response for unauthorized access
            return response()->json(['message' => 'Unauthorized'], 401);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
