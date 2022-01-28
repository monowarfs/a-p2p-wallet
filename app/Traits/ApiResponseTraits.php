<?php

namespace App\Traits;

trait ApiResponseTraits
{
    public function exceptionResponse(string $exception)
    {
        return response()->json([
            'code' => 500,
            'messages' => [$exception],
            'data' => null,
        ], 200);
    }

    public function invalidResponse(array $messages)
    {
        return response()->json([
            'code' => 422,
            'messages' => $messages,
            'data' => null,
        ], 200);
    }

    public function successResponse(array $messages, $data = null)
    {
        return response()->json([
            'code' => 200,
            'messages' => $messages,
            'data' => $data,
        ], 200);
    }

    public function respondWithAccessToken($accessToken)
    {
        return response()->json([
            'code' => 200,
            'messages' => [],
            'data' => $accessToken,
        ], 200);
    }

    public function unauthorizedResponse(array $messages)
    {
        return response()->json([
            'code' => 401,
            'messages' => $messages,
            'data' => '',
        ], 200);
    }

    public function respondInJSON(int $code, array $messages = [], $data = null)
    {
        return response()->json([
            'code' => $code,
            'messages' => $messages,
            'data' => $data,
        ]);
    }

    public function respondInJSONWithAdditional(int $code, $messages, $data = null, $per_page, $total)
    {
        return response()->json([
            'code' => $code,
            'messages' => $messages,
            'data' => $data,
            'data_additional' => [
                'per_page' => $per_page,
                'total' => $total,
            ],
        ]);
    }
}
