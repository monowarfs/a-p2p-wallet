<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\APIBaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class WalletInfoController extends APIBaseController
{
    public function __invoke(): JsonResponse
    {
        try {
            $user = User::with('wallet.currency')
                ->where('id', auth()->user()->id)
                ->first();

            $responsePayload = [
                'user' => [
                    'name' => $user->name,
                    'mobile_number' => $user->mobile_no,
                    'email' => $user->email,
                    'joined_at' => $user->created_at->format('Y-m-d H:i:s'),
                ],
                'wallet' => [
                    'ac_no' => $user->wallet->wallet_ac_no ?? '',
                    'currency' => [
                        'id' => $user->wallet->currency->id ?? '',
                        'code' => $user->wallet->currency->code ?? '',
                        'name' => $user->wallet->currency->name ?? '',
                    ],
                    'balance' => number_format((float) $user->wallet->balance, 2),
                ],
            ];

            return $this->respondInJSON(200, [], $responsePayload);
        } catch (\Exception | \Throwable $e) {
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
