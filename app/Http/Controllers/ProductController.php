<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Helpers\ApiResponse;

class ProductController extends Controller
{


    /**
     * @OA\Post(
     *      path="/api/products",
     *      tags={"Products"},
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"category_id", "subcategory_id", "name", "price", "amount"},
     *              @OA\Property(property="category_id", type="integer", example="1"),
     *              @OA\Property(property="subcategory_id", type="integer", example="1"),
     *              @OA\Property(property="name", type="string", example="Sport"),
     *              @OA\Property(property="price", type="number", format="float", example="10"),
     *              @OA\Property(property="amount", type="integer", example="5")
     *          )
     *      ),
     *      
     *      @OA\Response(
     *          response=200,
     *          description= "Product inserted successfully"
     *      ),
     * 
     *       @OA\Response(
     *          response=422,
     *          description= "Product failed to be inserted"
     *      )
     * )
     */


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


    /**
     * @OA\Get(
     *      path="/api/products",
     *      tags={"Products"},
     *      security={{"bearerAuth": {}}},
     *      
     *      @OA\Response(
     *          response=200,
     *          description= "Products retrieved successfully"
     *      ),
     * 
     *       @OA\Response(
     *          response=401,
     *          description= "Unauthorized"
     *      )
     * )
     */


    public function index()
    {
        $products = Product::with('category', 'subcategory')->get();

        return ApiResponse::success([
            'products' => $products,
        ], 'Products Retrieved Successfully');
    }


    /**
     * @OA\Get(
     *      path="/api/products/{id}",
     *      tags={"Products"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      
     *      @OA\Response(
     *          response=200,
     *          description= "Product retrieved successfully"
     *      ),
     * 
     *       @OA\Response(
     *          response=404,
     *          description= "Product not found"
     *      )
     * )
     */

    public function show($id)
    {
        $product = Product::with('category', 'subcategory')->findOrFail($id);

        return ApiResponse::success([
            'product' => $product,
        ], 'Product Retrieved Successfully');
    }


    /**
     * @OA\Put(
     *      path="/api/products/{id}",
     *      tags={"Products"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *        name="id",
     *        in="path",
     *        required=true,
     *        @OA\Schema(type="integer"),        
     *      ),
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"category_id", "subcategory_id", "name", "price", "amount"},
     *              @OA\Property(property="category_id", type="integer", example="1"),
     *              @OA\Property(property="subcategory_id", type="integer", example="1"),
     *              @OA\Property(property="name", type="string", example="Sport"),
     *              @OA\Property(property="price", type="number", format="float", example="10"),
     *              @OA\Property(property="amount", type="integer", example="5")
     *         ),
     *      ),
     *      
     *      @OA\Response(
     *          response=200,
     *          description= "Product updated successfully"
     *      ),
     * 
     *       @OA\Response(
     *          response=404,
     *          description= "Product not found"
     *      )
     * )
     */


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


    /**
     * @OA\Delete(
     *      path="/api/products/{id}",
     *      tags={"Products"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *        name="id",
     *        in="path",
     *        required=true,
     *        @OA\Schema(type="integer"),        
     *      ),
     *    
     *   
     *      @OA\Response(
     *          response=200,
     *          description= "Product deleted successfully"
     *      ),
     * 
     *       @OA\Response(
     *          response=404,
     *          description= "Product not found"
     *      )
     * )
     */


    public function destroy($id)
    {

        Product::findOrFail($id)->delete();

        return ApiResponse::success(message: "Product Deleted Successfully");
    }
}
