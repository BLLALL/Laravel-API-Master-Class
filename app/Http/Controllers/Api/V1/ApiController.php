<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\apiResponses;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use apiResponses;
    public function include(string $relationship): bool
    {
        $param = request()->get('include');

        if (!isset($param)) return false;

        $includedValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includedValues);
    }
}
