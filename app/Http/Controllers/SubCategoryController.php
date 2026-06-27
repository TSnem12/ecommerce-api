<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
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

    public function index()
    {
        $subcategories = SubCategory::with('category')->get();

        return ApiResponse::success([
            'subcategories' => $subcategories,
        ], 'SubCategories Retrieved Successfully');
    }


    public function show($id)
    {
        $subcategory = SubCategory::with('category')->findOrFail($id);

        return ApiResponse::success([
            'subcategory' => $subcategory,
        ], 'SubCategory Retrieved Successfully');
    }


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

    public function destroy($id)
    {

        SubCategory::findOrFail($id)->delete();

        return ApiResponse::success(message: "SubCategory Deleted Successfully");
    }
}
