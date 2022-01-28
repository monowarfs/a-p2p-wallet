<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\APIBaseController;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class VersionController extends APIBaseController
{
    public function __invoke(): JsonResponse
    {
        return $this->respondInJSON(200, [], [
            'server_time' => Carbon::now(),
            'api_version' => config('app.api_version')
        ]);
    }
}
