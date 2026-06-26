<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
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

    public function index()
    {
        $categories = Category::all();

        return ApiResponse::success([
            'categories' => $categories,
        ], 'Categories Retrieved Successfully');
    }


    public function show($id)
    {
        $category = Category::findOrFail($id);

        return ApiResponse::success([
            'category' => $category,
        ], 'Category Retrieved Successfully');
    }


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

    public function destroy($id)
    {

        Category::findOrFail($id)->delete();

        return ApiResponse::success(message: "Category Deleted Successfully");
    }
}
