<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Exceptions\Wallet\Transaction\Transaction\BusinessValidationEx;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Exceptions\OAuthServerException;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        OAuthServerException::class,
        \League\OAuth2\Server\Exception\OAuthServerException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Throwable $exception
     *
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        Log::critical($exception->getFile() . ' ' . $exception->getLine() . ' ' . $exception->getMessage());
        Log::info(request()->fullUrl());
        Log::info((array) request()->except(['password', 'pin']));

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof FatalError) {
            return response()->json([
                'code' => 500,
                'messages' => ['Sorry! We are unable to process your request at this moment. Try again later'],
                'data' => null,
            ], 500);
        }

        if($exception instanceof BusinessValidationEx){
            return response()->json([
                'code' => 422,
                'messages' => [$exception->getMessage()],
                'data' => null,
            ], 500);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return RedirectResponse|JsonResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            //return response()->json(['error' => 'Unauthenticated'], 401);
            return response()->json([
                'code' => 401,
                'messages' => ['Authentication failed'],
                'data' => null,
            ], 401);
        }
        return redirect()->guest(route('login'));
    }
}
