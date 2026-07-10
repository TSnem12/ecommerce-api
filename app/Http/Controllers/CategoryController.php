<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{


    /**
     * @OA\Post(
     *      path="/api/categories",
     *      tags={"Categories"},
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="Sport")
     *          )
     *      ),
     *      
     *      @OA\Response(
     *          response=200,
     *          description= "Category inserted successfully"
     *      ),
     * 
     *       @OA\Response(
     *          response=422,
     *          description= "Category failed to be inserted"
     *      )
     * )
     */


    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
        ]);


        $category = Category::create([
            'name' => $request->name
        ]);

        return ApiResponse::success([
            'category' => $category,
        ], 'Category Inserted Successfully');
    }


    /**
     * @OA\Get(
     *      path="/api/categories",
     *      tags={"Categories"},
     *      security={{"bearerAuth": {}}},
     *      
     *      @OA\Response(
     *          response=200,
     *          description= "Categories retrieved successfully"
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
        $categories = Category::all();

        return ApiResponse::success([
            'categories' => $categories,
        ], 'Categories Retrieved Successfully');
    }


    /**
     * @OA\Get(
     *      path="/api/categories/{id}",
     *      tags={"Categories"},
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
     *          description= "Category retrieved successfully"
     *      ),
     * 
     *       @OA\Response(
     *          response=404,
     *          description= "Category not found"
     *      )
     * )
     */


    public function show($id)
    {
        $category = Category::findOrFail($id);

        return ApiResponse::success([
            'category' => $category,
        ], 'Category Retrieved Successfully');
    }


    /**
     * @OA\Put(
     *      path="/api/categories/{id}",
     *      tags={"Categories"},
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
     *            required={"name"},
     *            @OA\Property(property="name", type="string", example="sport")
     *         ),
     *      ),
     *      
     *      @OA\Response(
     *          response=200,
     *          description= "Category updated successfully"
     *      ),
     * 
     *       @OA\Response(
     *          response=404,
     *          description= "Category not found"
     *      )
     * )
     */


    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = Category::findOrFail($id);

        $category->update([
            'name' => $request->name
        ]);

        return ApiResponse::success([
            'category' => $category
        ], "Category Updated Successfully");
    }


    /**
     * @OA\Delete(
     *      path="/api/categories/{id}",
     *      tags={"Categories"},
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
     *          description= "Category deleted successfully"
     *      ),
     * 
     *       @OA\Response(
     *          response=404,
     *          description= "Category not found"
     *      )
     * )
     */


    public function destroy($id)
    {

        Category::findOrFail($id)->delete();

        return ApiResponse::success(message: "Category Deleted Successfully");
    }
}
