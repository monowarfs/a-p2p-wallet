<?php

declare(strict_types=1);

namespace App\Library\CurrencyConversion\Fixer;

use App\Models\ConversionRate;
use App\Models\Currency;
use Carbon\Carbon;

class FixerBaseRateUpdater
{
    public function __construct()
    {
        $currencies = Currency::orderBy('code', 'ASC')->get();

        $conversionRates = (new FixerConverter())->getConversion();

        $baseId = $this->getTheBaseCurrencyId($currencies, $conversionRates['base']);

        if ($baseId === null) {
            return;
        }

        $list = $this->getTheUpdatedList($currencies, $conversionRates['rates'], $baseId);

        if (count($list) > 0) {
            ConversionRate::insert($list);
        }
    }

    private function getTheBaseCurrencyId($currencies, $baseCode)
    {
        foreach ($currencies as $currency) {
            if ($currency->code === $baseCode) {
                return $currency->id;
            }
        }
        return null;
    }

    private function getTheUpdatedList($currencies, $rates, $baseId): array
    {
        $list = [];
        foreach ($currencies as $currency) {
            foreach ($rates as $key => $value) {
                if ($currency->code === $key) {
                    $list[] = [
                        'from_id' => $baseId,
                        'to_id' => $currency->id,
                        'rate' => $value,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'provider' => 'fixer',
                    ];
                    break;
                }
            }
        }
        return $list;
    }
}
