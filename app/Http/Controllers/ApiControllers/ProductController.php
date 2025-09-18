<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function addProduct(Request $request)
    {

        try {
            $request->validate([

                'price' => 'required|numeric',


            ]);

            $product = new Product;
            $product->name = $request->input('name');
            $product->price = $request->input('price');
            $product->description = $request->input('description');

            if ($request->hasFile('file')) {
                $product->file_path = $request->file('file')->store('products');
            }

            $product->save();

            return response()->json($product, 201); // 201 Created
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500); // 500 Internal Server Error
        }
    }

    function list_Products()
    {
        $Product = Product::all();
        return $Product;
    }

    function delete_Product($id)
    {
        try {
            $product = Product::findOrFail($id);

            $file = $product->file_path;
            if ($file && file_exists($file)) {
                unlink($file);
            }

            $product->delete();

            return "Success";
        } catch (\Exception $e) {
            return "Not found";
        }
    }

    function getProduct($id)
    {
        $Product = Product::find($id);
        return $Product;
    }

    function updateProduct($id, Request $request)
    {
        $product = Product::find($id);
        try {

            $product->name = $request->input('name');
            $product->price = $request->input('price');
            $product->description = $request->input('description');

            if ($request->hasFile('file')) {
                $uploadedFile = $request->file('file');
                $file_path = $uploadedFile->store('products');
                $oldFilePath = $product->file_path;
                if ($oldFilePath && file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
                $product->file_path = $file_path;
            }
            $product->save();
            // Return the updated product in the response
            return response()->json($product, 200); // 200 OK
        } catch (\Exception $e) {
            // Return a JSON response with the error message and 500 status code
            return response()->json(['error' => $e->getMessage()], 500); // 500 Internal Server Error
        }
    }
}
