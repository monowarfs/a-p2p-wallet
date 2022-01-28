<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\APIBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class RefreshTokenController extends APIBaseController
{
    public function __invoke(): JsonResponse
    {
        try{
            $user = auth()->user();

            DB::beginTransaction();
            DB::statement("UPDATE oauth_access_tokens SET revoked = 1 WHERE user_id = {$user->id}");
            $passportToken = $user->createToken($user->mobile_no)->accessToken;
            DB::commit();

            $responsePayload = [
                'token' => $passportToken,
            ];

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
