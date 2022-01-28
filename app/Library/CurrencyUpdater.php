<?php

namespace App\Library;

use App\Constant\ConversionServiceProvider;
use App\Library\CurrencyConversion\Fixer\FixerBaseRateUpdater;
use App\Library\CurrencyConversion\OpenExchangeRates\OpenExchangeRateBaseRateUpdater;
use App\Models\ConversionRate;
use Carbon\Carbon;

class CurrencyUpdater
{
    public function __construct()
    {
        $lastEntry = ConversionRate::latest()->first();

        if(!$lastEntry)
        {
            $this->update();
        }

        \Log::info($lastEntry->toArray());

        $timeDifference = Carbon::now()
                            ->diffInSeconds(Carbon::createFromDate($lastEntry->created_at->format('Y-m-d H:i:s')));

        \Log::info("Time Difference :". $timeDifference);

        if($timeDifference > config('biz_settings.conversion_rate_update_frequency'))
        {
            $this->update();
        }
    }

    private function update()
    {
        if(config('biz_settings.conversion_rate_update_by') == ConversionServiceProvider::FIXER)
        {
            return (new FixerBaseRateUpdater());
        }

        if(config('biz_settings.conversion_rate_update_by') == ConversionServiceProvider::OPEN_EXCHANGE_RATES)
        {
            return (new OpenExchangeRateBaseRateUpdater());
        }
    }
}
