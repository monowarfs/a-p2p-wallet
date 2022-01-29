<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Transaction\SendMoney;

use App\Exceptions\Wallet\Transaction\Transaction\BusinessValidationEx;
use App\Http\Controllers\APIBaseController;
use App\Http\Requests\API\V1\Transaction\SendMoney\ExecuteRequest;
use App\Library\Wallet\Transaction\SendMoneyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ExecuteController extends APIBaseController
{
    public function __invoke(ExecuteRequest $request): JsonResponse
    {
        try {
            return $this->respondInJSON(
                200,
                [],
                (new SendMoneyService(
                    auth()->user()->mobile_no,
                    $request->receiver_mobile_number,
                    $request->amount
                ))->execute()
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
