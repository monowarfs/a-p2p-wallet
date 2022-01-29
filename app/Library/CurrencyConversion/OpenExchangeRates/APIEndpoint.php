<?php

declare(strict_types=1);

namespace App\Library\CurrencyConversion\OpenExchangeRates;

class APIEndpoint
{
    public const API_BASE_URL = 'https://openexchangerates.org/api/';
    public const API_CURRENCY = 'currencies.json';
    public const API_LATEST = 'latest.json';
    public const API_CONVERT = 'convert';

    public function getCurrenciesEndpoint()
    {
        return self::API_BASE_URL.self::API_CURRENCY;
    }

    public function getLatestEndpoint()
    {
        return self::API_BASE_URL.self::API_LATEST;
    }

    public function getConvertEndpoint()
    {
        return self::API_BASE_URL.self::API_CONVERT;
    }
}
