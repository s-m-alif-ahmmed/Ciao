<?php

namespace App\Http\Controllers\API\Shop;

use App\Helpers\Helper;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopListController
{
    public function index(Request $request)
    {
        $data = Shop::where('status', 'active')
            ->withCount('orders') // Adds 'orders_count' field
            ->with('images')
            ->latest()
            ->get();
        if (count($data) == 0) {
            return Helper::jsonErrorResponse('No Shops found!', 404);
        }
        return Helper::jsonResponse(true, 'Shops Retrieved Successfully', 200, $data);
    }

    public function shopDetails(Request $request, $id)
    {
        $data = Shop::where('status', 'active')
            ->where('id', $id)
            ->with('images')
            ->first();
        if (!$data) {
            return Helper::jsonResponse(true, 'No Shop found!', 200,[]);
        }
        return Helper::jsonResponse(true, 'Shop details Retrieved Successfully', 200, $data);
    }
}
