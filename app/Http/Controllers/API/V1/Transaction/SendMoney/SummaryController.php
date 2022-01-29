<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Transaction\SendMoney;

use App\Exceptions\Wallet\Transaction\Transaction\BusinessValidationEx;
use App\Http\Controllers\APIBaseController;
use App\Http\Requests\API\V1\Transaction\SendMoney\SummaryRequest;
use App\Library\Wallet\Transaction\SendMoneyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SummaryController extends APIBaseController
{
    public function __invoke(SummaryRequest $request): JsonResponse
    {
        try {
            $response = (new SendMoneyService(
                auth()->user()->mobile_no,
                $request->receiver_mobile_number,
                $request->amount
            ))->getSummary();

            return $this->respondInJSON(
                200,
                [],
                $response
            );
        } catch (BusinessValidationEx $e) {
            Log::error(
                $e->getFile() . ' ' .
                $e->getLine() . ' ' .
                $e->getMessage()
            );
            return $this->invalidResponse([$e->getMessage()]);
        } catch (\Exception | \Throwable $e) {
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
