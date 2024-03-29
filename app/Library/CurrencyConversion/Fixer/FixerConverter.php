<?php

declare(strict_types=1);

namespace App\Library\CurrencyConversion\Fixer;

use App\Library\Common\CurrencyConversion;
use App\Library\CurrencyConversion\Fixer\APIService\FixerAPIService;
use Illuminate\Support\Facades\Log;

class FixerConverter implements CurrencyConversion
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
