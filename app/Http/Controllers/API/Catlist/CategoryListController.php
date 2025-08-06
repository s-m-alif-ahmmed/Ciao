<?php

namespace App\Http\Controllers\API\Catlist;

use App\Helpers\Helper;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryListController
{
    public function index(Request $request)
    {
        $data = Category::where('status', 'active')->get();
        if (count($data) == 0) {
            return Helper::jsonErrorResponse('No Categories data found!', 404);
        }
        return Helper::jsonResponse(true, 'Categories Retrieved Successfully', 200, $data);
    }
}
