<?php

namespace App\Http\Controllers\API\Product;

use App\Helpers\Helper;
use App\Models\Category;
use App\Models\Favourite;
use App\Models\Product;
use App\Models\Shop;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductListController
{
    public function index($id)
    {
        $shop = Shop::findOrFail($id);
        $data = Product::where('status', 'active')
            ->where('shop_id', $shop->id)
            ->latest()
            ->select(['id', 'name', 'price', 'quantity', 'thumbnail', 'category_id', 'sub_category_id', 'shop_id'])
            ->with(['category', 'subcategory', 'shop', 'images'])
            ->get();

        if (count($data) == 0) {
            return Helper::jsonErrorResponse('No Products found!', 404);
        }
        return Helper::jsonResponse(true, 'Products Retrieved Successfully', 200, $data);
    }

    public function categoryProduct($shop_id, $id)
    {
        $shop = Shop::where('status', 'active')->where('id', $shop_id)->first();
        $category = Category::where('status', 'active')->where('id', $id)->first();

        $data = Product::where('status', 'active')
            ->where('shop_id', $shop->id)
            ->where('category_id', $category->id)
            ->latest()
            ->select(['id', 'name', 'price', 'quantity', 'thumbnail', 'category_id', 'sub_category_id', 'shop_id'])
            ->with(['category', 'subcategory', 'shop', 'images'])
            ->get();

        if (count($data) == 0) {
            return Helper::jsonErrorResponse('No Products found!', 404);
        }
        return Helper::jsonResponse(true, 'Products Retrieved Successfully', 200, $data);
    }

    public function subCategoryProduct($shop_id, $category_id, $id)
    {
        $shop = Shop::where('status', 'active')->where('id', $shop_id)->first();
        $category = Category::where('status', 'active')->where('id', $category_id)->first();
        $sub_category = SubCategory::where('status', 'active')->where('id', $id)->first();

        $data = Product::where('status', 'active')
            ->where('shop_id', $shop->id)
            ->where('category_id', $category->id)
            ->where('sub_category_id', $sub_category->id)
            ->latest()
            ->select(['id', 'name', 'price', 'quantity', 'thumbnail', 'category_id', 'sub_category_id', 'shop_id'])
            ->with(['category', 'subcategory', 'shop', 'images'])
            ->get();

        if (count($data) == 0) {
            return Helper::jsonErrorResponse('No Products found!', 404);
        }
        return Helper::jsonResponse(true, 'Products Retrieved Successfully', 200, $data);
    }

    public function details($id)
    {
        try {
            // Fetch the product with its related data (e.g., category, subcategory, shop, and images)
            $product = Product::where('status', 'active')->where('id', $id)->with([
                'shop' => function ($query) {
                    $query->where('status', 'active')->get();
                },
                'category' => function ($query) {
                    $query->where('status', 'active')->select(['id', 'name']);
                },
                'subCategory' => function ($query) {
                    $query->where('status', 'active')->select(['id', 'name']);
                },
                'images' => function ($query) {
                    $query->select(['id', 'product_id', 'image_path']);
                }
            ])->first();

            if (!$product) {
                return Helper::jsonErrorResponse('No Products Found!', 404);
            }
            $user = Auth::id();
            $favourite = Favourite::where('user_id', $user)->where('product_id', $id)->first();

            if ($favourite) {
                $is_favourite = true;
            }else{
                $is_favourite = false;
            }

            return Helper::jsonResponse(true, 'Product details retrieved successfully.', 200, [ 'is_favourite' => $is_favourite ] + $product->toArray());
        } catch (\Exception $e) {
            return Helper::jsonResponse(false, 'Error fetching product details.', 200,['is_favourite' => false]);
        }
    }
}
