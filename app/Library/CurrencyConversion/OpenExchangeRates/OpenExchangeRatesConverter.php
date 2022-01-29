<?php

declare(strict_types=1);

namespace App\Library\CurrencyConversion\OpenExchangeRates;

use App\Library\Common\CurrencyConversion;
use App\Library\CurrencyConversion\OpenExchangeRates\APIService\APIService;
use Illuminate\Support\Facades\Log;

class OpenExchangeRatesConverter implements CurrencyConversion
{
    private APIService $apiService;

    public function __construct()
    {
        $this->apiService = new APIService();
    }

    public function getSymbols()
    {
        try {
            return $this->apiService->currencyListService();
        } catch (\Exception|\Throwable $e) {
            Log::error(
                $e->getFile() . ' ' .
                $e->getLine() . ' ' .
                $e->getMessage()
            );
            Log::error($e);
        }
    }

    public function getConversion()
    {
        try {
            return $this->apiService->conversionService();
        } catch (\Exception|\Throwable $e) {
            Log::error(
                $e->getFile() . ' ' .
                $e->getLine() . ' ' .
                $e->getMessage()
            );
            Log::error($e);
        }
    }
}
