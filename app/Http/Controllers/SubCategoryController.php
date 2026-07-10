<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{


    /**
     * @OA\Post(
     *      path="/api/subcategories",
     *      tags={"SubCategories"},
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"category_id", "name"},
     *              @OA\Property(property="category_id", type="integer", example="1"),
     *              @OA\Property(property="name", type="string", example="Sport")
     *          )
     *      ),
     *      
     *      @OA\Response(
     *          response=200,
     *          description= "SubCategory inserted successfully"
     *      ),
     * 
     *       @OA\Response(
     *          response=422,
     *          description= "SubCategory failed to be inserted"
     *      )
     * )
     */



    public function store(Request $request)
    {

        $request->validate([
            'category_id' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);


        $subcategory = SubCategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name
        ]);

        return ApiResponse::success([
            'subcategory' => $subcategory,
        ], 'SubCategory Inserted Successfully');
    }


    /**
     * @OA\Get(
     *      path="/api/subcategories",
     *      tags={"SubCategories"},
     *      security={{"bearerAuth": {}}},
     *      
     *      @OA\Response(
     *          response=200,
     *          description= "SubCategories retrieved successfully"
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
        $subcategories = SubCategory::with('category')->get();

        return ApiResponse::success([
            'subcategories' => $subcategories,
        ], 'SubCategories Retrieved Successfully');
    }



    /**
     * @OA\Get(
     *      path="/api/subcategories/{id}",
     *      tags={"SubCategories"},
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
     *          description= "SubCategory retrieved successfully"
     *      ),
     * 
     *       @OA\Response(
     *          response=404,
     *          description= "SubCategory not found"
     *      )
     * )
     */


    public function show($id)
    {
        $subcategory = SubCategory::with('category')->findOrFail($id);

        return ApiResponse::success([
            'subcategory' => $subcategory,
        ], 'SubCategory Retrieved Successfully');
    }


    /**
     * @OA\Put(
     *      path="/api/subcategories/{id}",
     *      tags={"SubCategories"},
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
     *            required={"category_id", "name"},
     *            @OA\Property(property="category_id", type="integer", example="1"),
     *            @OA\Property(property="name", type="string", example="Sport")
     *         ),
     *      ),
     *      
     *      @OA\Response(
     *          response=200,
     *          description= "SubCategory updated successfully"
     *      ),
     * 
     *       @OA\Response(
     *          response=404,
     *          description= "SubCategory not found"
     *      )
     * )
     */


    public function update(Request $request, $id)
    {

        $request->validate([
            'category_id' => 'required|integer',
            'name' => 'required|string|max:255'
        ]);

        $subcategory = SubCategory::findOrFail($id);

        $subcategory->update([
            'category_id' => $request->category_id,
            'name' => $request->name
        ]);

        $subcategory->refresh();

        return ApiResponse::success([
            'subcategory' => $subcategory
        ], "SubCategory Updated Successfully");
    }


    /**
     * @OA\Delete(
     *      path="/api/subcategories/{id}",
     *      tags={"SubCategories"},
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
     *          description= "SubCategory deleted successfully"
     *      ),
     * 
     *       @OA\Response(
     *          response=404,
     *          description= "SubCategory not found"
     *      )
     * )
     */


    public function destroy($id)
    {

        SubCategory::findOrFail($id)->delete();

        return ApiResponse::success(message: "SubCategory Deleted Successfully");
    }
}
