<?php

namespace App\Console\Commands;

use App\Library\CurrencyUpdater;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CurrencyRateUpdateScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency-rate:updater';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will help to update the conversion rate.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        Log::info("CurrencyRateUpdateScheduler executing at :".Carbon::now());
        new CurrencyUpdater();
    }
}
