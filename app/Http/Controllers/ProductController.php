<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Helpers\ApiResponse;

class ProductController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'category_id' => 'required|integer',
            'subcategory_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'amount' => 'required|integer',
        ]);


        $product = Product::create([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'name' => $request->name,
            'price' => $request->price,
            'amount' => $request->amount
        ]);

        return ApiResponse::success([
            'product' => $product,
        ], 'Product Inserted Successfully');
    }

    public function index()
    {
        $products = Product::with('category', 'subcategory')->get();

        return ApiResponse::success([
            'products' => $products,
        ], 'Products Retrieved Successfully');
    }


    public function show($id)
    {
        $product = Product::with('category', 'subcategory')->findOrFail($id);

        return ApiResponse::success([
            'product' => $product,
        ], 'Product Retrieved Successfully');
    }


    public function update(Request $request, $id)
    {

        $request->validate([
            'category_id' => 'required|integer',
            'subcategory_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'amount' => 'required|integer',
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'name' => $request->name,
            'price' => $request->price,
            'amount' => $request->amount
        ]);

        $product->refresh();

        return ApiResponse::success([
            'product' => $product
        ], "Product Updated Successfully");
    }

    public function destroy($id)
    {

        Product::findOrFail($id)->delete();

        return ApiResponse::success(message: "Product Deleted Successfully");
    }
}
