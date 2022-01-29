<?php

declare(strict_types=1);

namespace App\Library;

use App\Models\ConversionRate;

class CurrencyConverter
{
    private ConversionRate $fromConversionRate;
    private ConversionRate $toConversionRate;
    private $amount;
    private $rate;
    private $convertedAmount;

    public function __construct(int $from, int $to, $amount)
    {
        try {
            $this->amount = $amount;

            $this->fromConversionRate = ConversionRate::where('to_id', $from)
                ->latest()
                ->first();

            $this->toConversionRate = ConversionRate::where('to_id', $to)
                ->where('provider', $this->fromConversionRate->provider)
                ->latest()
                ->first();

            $this->rate = 1 * $this->toConversionRate->rate / $this->fromConversionRate->rate;

            $this->convertedAmount = $this->rate * $amount ;

        } catch (\Exception|\Throwable $e) {
            throw new \Exception($e->getMessage(), 0, $e);
        }
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function getConvertedAmount()
    {
        return $this->convertedAmount;
    }

    public function getFromConversionRate(): ConversionRate
    {
        return $this->fromConversionRate;
    }

    public function getToConversionRate(): ConversionRate
    {
        return $this->toConversionRate;
    }

    public function getRateObjects(): array
    {
        return [
            'from' => (array)$this->fromConversionRate,
            'to' => (array)$this->toConversionRate,
        ];
    }
}
