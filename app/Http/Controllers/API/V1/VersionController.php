<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\APIBaseController;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class VersionController extends APIBaseController
{
    public function __invoke(): JsonResponse
    {
        return $this->respondInJSON(200, [], [
            'server_time' => Carbon::now()->format('Y-m-d H:i:s'),
            'api_version' => config('app.api_version'),
        ]);
    }
}
