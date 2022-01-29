<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Library\CurrencyUpdater;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CurrencyRateUpdateScheduler extends Command
{
    protected $signature = 'currency-rate:updater';

    protected $description = 'This command will update conversion rate.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        Log::info('CurrencyRateUpdateScheduler executing at :'.Carbon::now());
        new CurrencyUpdater();
    }
}
