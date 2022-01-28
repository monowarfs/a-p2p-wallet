<?php

namespace App\Library;


use App\Models\ConversionRate;

class CurrencyConverter
{
    public function do(int $from, int $to, $amount)
    {
        if($amount == 0) return 0.0;

        if($from == $to ) return $amount;

        $fromConversionRate = ConversionRate::where('to_id', $from)
            ->latest()
            ->first();

        $toConversionRate = ConversionRate::where('to_id', $to)
            ->where('provider', $fromConversionRate->provider)
            ->latest()
            ->first();

        return  (1*$amount*$toConversionRate->rate) / ($fromConversionRate->rate);
    }
}
