<?php

namespace App\Http\Controllers\API\Setting;

use App\Helpers\Helper;
use App\Models\Product;
use App\Models\Shop;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController
{
    public function index()
    {
        $system = SystemSetting::select(['id', 'title', 'email', 'system_name', 'copyright_text', 'logo', 'favicon', 'description'])->get();

        if (count($system) == 0) {
            return Helper::jsonErrorResponse('No data found!', 404);
        }
        return Helper::jsonResponse(true, 'Data Retrieved Successfully', 200, $system);
    }
}
