<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Auth\LogIn;

use App\Http\Controllers\APIBaseController;
use App\Http\Requests\API\V1\Auth\LogIn\LogInRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoginController extends APIBaseController
{
    public function __invoke(LogInRequest $request): JsonResponse
    {
        try {
            if (Auth::attempt([
                'mobile_no' => $request->input('mobile_number'),
                'password' => $request->input('password'),
            ])) {
                $user = auth()->user();

                DB::beginTransaction();
                DB::statement(
                    "UPDATE oauth_access_tokens SET revoked = 1 WHERE user_id = {$user->id}"
                );
                $passportToken = $user
                    ->createToken($user->mobile_no)
                    ->accessToken;
                DB::commit();

                $responsePayload = [
                    'token' => $passportToken,
                ];

                return $this->respondInJSON(
                    200,
                    [trans('messages.welcome')],
                    $responsePayload
                );
            }

            return $this->respondInJSON(
                422,
                [trans('messages.credentials_do_not_match')],
                []
            );
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
