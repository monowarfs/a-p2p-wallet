<?php

declare(strict_types=1);

namespace App\Library\CurrencyConversion\OpenExchangeRates\APIService;

use App\Library\Common\ThirdParyService;
use App\Library\CurrencyConversion\OpenExchangeRates\APIEndpoint;
use App\Library\HttpCaller;
use Illuminate\Support\Facades\Log;

class APIService implements ThirdParyService
{
    public APIEndpoint $api;
    private HttpCaller $httpCaller;

    public function __construct()
    {
        $this->api = new APIEndpoint();
        $this->httpCaller = new HttpCaller($this->api::API_BASE_URL);
    }

    public function currencyListService()
    {
        try {
            return $this->httpCaller->makeGetRequest(
                $this->api::API_CURRENCY,
                [
                    'app_id' => config('services.openexchangerates.app_id'),
                ]
            );
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
            return $this->httpCaller->makeGetRequest(
                $this->api::API_LATEST,
                [
                    'app_id' => config('services.openexchangerates.app_id'),
                ]
            );
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
}
