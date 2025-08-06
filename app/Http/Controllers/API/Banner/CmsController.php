<?php

namespace App\Http\Controllers\API\Banner;

use App\Helpers\Helper;
use App\Models\CMS;
use App\Models\Shop;
use Illuminate\Http\Request;

class CmsController
{
    public function index(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);

        if (!$shop) {
            return Helper::jsonErrorResponse('No shop found!', 404);
        }

        $data = CMS::where('status', 'active')->where('shop_id', $shop->id)->select(['id', 'shop_id', 'banner_image'])->get();

        if (count($data) == 0) {
            return Helper::jsonErrorResponse('No Banner found!', 404);
        }
        return Helper::jsonResponse(true, 'Banner Retrieved Successfully', 200, $data);
    }
}
