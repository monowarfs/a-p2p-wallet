<?php

namespace App\Http\Controllers\API\V1\Transaction\SendMoney;

use App\Http\Controllers\APIBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SummaryController extends APIBaseController
{
    public function __invoke(): JsonResponse
    {
        try{

            $responsePayload = [];

            return $this->respondInJSON(
                200,
                [trans('messages.refresh_token')],
                $responsePayload
            );

        }catch (\Exception $e){
            Log::error($e);
            Log::error(
                $e->getFile() . ' ' .
                $e->getLine() . ' ' .
                $e->getMessage()
            );

            return $this->exceptionResponse(
                trans('messages.internal_server_error')
            );
        }

    }
}
