<?php

namespace App\Library\CurrencyConversion\OpenExchangeRates;

class ResponseStatusCode
{
    public const RSC400 = "Client requested rates for an unsupported base currency";
    public const RSC401 = "Client did not provide an App ID";
    public const RSC403 = "Access restricted for repeated over-use (status: 429), or other reason given in ‘description’ (403).";
    public const RSC404 = "Client requested a non-existent resource/route";
    public const RSC429 = "Client doesn’t have permission to access requested route/feature";

    public function getText($sl_no)
    {
        switch ($sl_no) {
            case 400:
                return self::RSC400;
            case 401:
                return self::RSC401;
            case 403:
                return self::RSC403;
            case 404:
                return self::RSC404;
            case 429:
                return self::RSC429;
        }
    }
}


