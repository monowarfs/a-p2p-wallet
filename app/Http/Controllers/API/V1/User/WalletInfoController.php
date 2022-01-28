<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\APIBaseController;
use Illuminate\Http\JsonResponse;

class WalletInfoController extends APIBaseController
{
    public function __invoke(): JsonResponse
    {
        $data = [];
        return $this->respondInJSON(200, [], $data);
    }
}
