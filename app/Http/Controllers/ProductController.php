<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function index()
        {
            $products = Product::all();
    
            return response()->json([
                "products" => $products
            ], 200);
        }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $request->validate([
           'title' => 'required',
           'description' => 'required',
           'image' => 'required|string',
           'price' => 'required|numeric',
          "lat"=> "required|numeric",
            "lng"=> "required|numeric",
           'owners_name' => 'required',
           'owners_email' => 'required|email',
           'owners_phone' => 'required|numeric|digits:11|regex:/^([0-9\s\-\+\(\)]*)$/',
       ]);

       $product = Product::create([
           'title' => $request->title,
           'description' => $request->description,
           'image' => $request->image,
           'price' => $request->price,
           'lat' => $request->lat,
              'lng' => $request->lng,
           'owners_name' => $request->owners_name,
           'owners_email' => $request->owners_email,
           'owners_phone' => $request->owners_phone,
       ]);

       return response()->json([
           "product" => $product
       ], 201);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Product $product)
    {
       // destroy product by id
         $request->validate(['id' => 'required|integer|exists:products,id']);

         $product = Product::find($request->id);

         $product->delete();

         return response()->json([
             "message" => "product deleted"
         ], 200);
    }

    
}
