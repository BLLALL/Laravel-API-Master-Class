<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\apiResponses;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class ApiController extends Controller
{
    use apiResponses;

    protected $policyClass;
    public function include(string $relationship): bool
    {
        $param = request()->get('include');

        if (!isset($param)) return false;

        $includedValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includedValues);
    }

    public function isAble($ability, $targetModel)
    {
        try {
            return Gate::authorize($ability, [$targetModel, $this->policyClass]);
        }catch (AuthorizationException $e) {
            return $this->error("You're not authorized to change that resource"  . $e->getMessage(), 401);

        }
    }
}
