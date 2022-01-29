<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;

class APIBaseController extends Controller
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    public function exceptionResponse(string $exception): JsonResponse
    {
        return response()->json([
            'code' => 500,
            'messages' => [$exception],
            'data' => null,
        ], 200);
    }

    public function invalidResponse(array $messages): JsonResponse
    {
        return response()->json([
            'code' => 422,
            'messages' => $messages,
            'data' => null,
        ], 200);
    }

    public function respondInJSON(
        int $code,
        array $messages = [],
        $data = null
    ): JsonResponse {
        return response()->json([
            'code' => $code,
            'messages' => $messages,
            'data' => $data,
        ]);
    }

    public function respondInJSONWithAdditional(
        int $code,
        array $messages,
        $data = null,
        int $per_page,
        int $total
    ): JsonResponse {
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
