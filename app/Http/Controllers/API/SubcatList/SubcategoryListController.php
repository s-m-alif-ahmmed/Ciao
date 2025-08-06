<?php

namespace App\Http\Controllers\API\SubcatList;

use App\Helpers\Helper;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubcategoryListController
{

    public function index(Request $request, $id)
{
    try {
        // Find Category
        $category = Category::findOrFail($id);

        // Category's SubCategory 
        $subcategories = SubCategory::where('category_id', $id)
            ->where('status', 'active')
            ->get();

        // If not found SubCategory
        if ($subcategories->isEmpty()) {
            return Helper::jsonErrorResponse('No subcategories found for this category!', 404);
        }

        return Helper::jsonResponse(true, 'Subcategories retrieved successfully.', 200, $subcategories);

    } catch (ModelNotFoundException $e) {
        return Helper::jsonErrorResponse('Category not found!', 404);
    } catch (\Exception $e) {
        return Helper::jsonErrorResponse($e->getMessage(), 422);
    }
}
    
}
