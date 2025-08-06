<?php

namespace App\Http\Controllers\API\DynamicPage;

use App\Helpers\Helper;
use App\Models\DynamicPage;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DynamicPageController
{
    public function index($id)
    {
        $data = DynamicPage::where('status', 'active')
            ->select(['id', 'slug'])
            ->find($id);

        if (!$data) {
            return Helper::jsonErrorResponse('No Dynamic Page found!', 404);
        }

        return Helper::jsonResponse(true, 'Dynamic Page Retrieved Successfully', 200, $data);

    }

}
