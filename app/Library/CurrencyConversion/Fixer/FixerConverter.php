<?php

namespace App\Library\CurrencyConversion\Fixer;

use App\Library\CommonInterfaces\CurrencyConversionInterface;
use App\Library\CurrencyConversion\Fixer\APIService\FixerAPIService;
use Illuminate\Support\Facades\Log;

class FixerConverter implements CurrencyConversionInterface
{

    private FixerAPIService $fixerAPIService;

    public function __construct()
    {
        $this->fixerAPIService = new FixerAPIService();
    }

    public function getSymbols()
    {
        try {
            return $this->fixerAPIService->symbolsService();
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
            return $this->fixerAPIService->conversionService();
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
