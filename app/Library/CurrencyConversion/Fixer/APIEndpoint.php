<?php

declare(strict_types=1);

namespace App\Library\CurrencyConversion\Fixer;

class APIEndpoint
{
    public const API_BASE_URL = 'http://data.fixer.io/api/';
    public const API_SYMBOLS = 'symbols';
    public const API_LATEST = 'latest';
    public const API_CONVERT = 'convert';
    public const API_FLUCTUATION = 'fluctuation';

    public function getSymbolsEndpoint()
    {
        return self::API_BASE_URL.self::API_SYMBOLS;
    }

    public function getLatestEndpoint()
    {
        return self::API_BASE_URL.self::API_LATEST;
    }

    public function getConvertEndpoint()
    {
        return self::API_BASE_URL.self::API_CONVERT;
    }

    public function getFluctuationEndpoint()
    {
        return self::API_BASE_URL.self::API_FLUCTUATION;
    }
}
