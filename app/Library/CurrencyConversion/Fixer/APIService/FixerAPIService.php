<?php

declare(strict_types=1);

namespace App\Library\CurrencyConversion\Fixer\APIService;

use App\Library\Common\ThirdParyService;
use App\Library\CurrencyConversion\Fixer\APIEndpoint;
use App\Library\CurrencyConversion\Fixer\ResponseStatusCode;
use App\Library\HttpCaller;
use App\Models\Currency;
use App\Notifications\ServiceConsumedAlertNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class FixerAPIService implements ThirdParyService
{
    public APIEndpoint $api;
    private HttpCaller $httpCaller;

    public function __construct()
    {
        $this->api = new APIEndpoint();
        $this->httpCaller = new HttpCaller($this->api::API_BASE_URL);
    }

    public function symbolsService()
    {
        try {
            $response = $this->httpCaller->makeGetRequest(
                $this->api::API_SYMBOLS,
                [
                    'access_key' => config('services.fixer.key'),
                ]
            );

            return $this->validateResponse($response);
        } catch (\Exception|\Throwable $e) {
            Log::error(
                $e->getFile() . ' ' .
                $e->getLine() . ' ' .
                $e->getMessage()
            );
            Log::error($e);
            return $e;
        }
    }

    public function conversionService()
    {
        try {
            $symbolsList = implode(
                ',',
                Currency::where('status', 1)
                    ->get()
                    ->pluck('code')
                    ->toArray()
            );

            $response = $this->httpCaller->makeGetRequest(
                $this->api::API_LATEST,
                [
                    'access_key' => config('services.fixer.key'),
                    'symbols' => $symbolsList,
                ]
            );

            return $this->validateResponse($response);
        } catch (\Exception|\Throwable $e) {
            Log::error(
                $e->getFile() . ' ' .
                $e->getLine() . ' ' .
                $e->getMessage()
            );
            Log::error($e);
            return $e;
        }
    }

    private function validateResponse($response)
    {
        try {
            $response = json_decode(
                $response,
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            if (isset($response['success'])) {
                if ($response['success'] === true) {
                    return $response;
                }

                if ($response['success'] === false) {
                    if (isset($response['error']['code'])) {
                        Log::error(
                            (new ResponseStatusCode())
                                ->getText($response['error']['code'])
                        );

                        // generate alert if api-limit-exceeds
                        if ($response['error']['code'] === 104) {
                            Notification::route(
                                'mail',
                                config('biz_settings.service_consumed_email_to')
                            )->notify(new ServiceConsumedAlertNotification(
                                'Fixer',
                                $this->api::API_BASE_URL
                            ));
                        }
                    }

                    throw new \Exception($response['error']['info']);
                }
            }

            throw new \Exception('Invalid Response Format');
        } catch (\Exception|\Throwable $e) {
            Log::error($e);
            return $e;
        }
    }
}
