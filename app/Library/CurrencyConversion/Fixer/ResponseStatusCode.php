<?php

namespace App\Library\CurrencyConversion\Fixer;

class ResponseStatusCode
{
    public const RSC101 = 'No API Key was specified or an invalid API Key was specified.';
    public const RSC102 = 'The account this API request is coming from is inactive.';
    public const RSC103 = 'The requested API endpoint does not exist.';
    public const RSC104 = 'The maximum allowed API amount of monthly API requests has been reached.';
    public const RSC105 = 'The current subscription plan does not support this API endpoint.';
    public const RSC106 = 'The current request did not return any results.';

    public const RSC201 = 'An invalid base currency has been entered.';
    public const RSC202 = 'One or more invalid symbols have been specified.';

    public const RSC301 = 'No date has been specified. [historical]';
    public const RSC302 = 'An invalid date has been specified. [historical, convert]';

    public const RSC403 = 'No or an invalid amount has been specified. [convert]';
    public const RSC404 = 'The requested resource does not exist.';

    public const RSC501 = 'No or an invalid timeframe has been specified. [timeseries]';
    public const RSC502 = 'No or an invalid "start_date" has been specified. [timeseries, fluctuation]';
    public const RSC503 = 'No or an invalid "end_date" has been specified. [timeseries, fluctuation]';
    public const RSC504 = 'An invalid timeframe has been specified. [timeseries, fluctuation]';
    public const RSC505 = 'The specified timeframe is too long, exceeding 365 days. [timeseries, fluctuation]';

    public function getText($sl_no)
    {
        switch ($sl_no) {
            case 101:
                return self::RSC101;
            case 102:
                return self::RSC102;
            case 103:
                return self::RSC103;
            case 104:
                return self::RSC104;
            case 105:
                return self::RSC105;
            case 106:
                return self::RSC106;

            case 201:
                return self::RSC201;
            case 202:
                return self::RSC202;

            case 301:
                return self::RSC301;
            case 302:
                return self::RSC302;

            case 403:
                return self::RSC403;
            case 404:
                return self::RSC404;

            case 501:
                return self::RSC501;
            case 502:
                return self::RSC502;
            case 503:
                return self::RSC503;
            case 504:
                return self::RSC504;
            case 505:
                return self::RSC505;
        }
    }
}


