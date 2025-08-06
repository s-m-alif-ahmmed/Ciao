<?php

namespace App\Http\Controllers\API\Tax;

use App\Helpers\Helper;
use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController
{
    public function index()
    {
        $tax = Tax::where('status', 'active')->select('id', 'tax')->first();

        return Helper::jsonResponse(true, 'Tax Retrieved Successfully', 200, $tax);
    }

}
