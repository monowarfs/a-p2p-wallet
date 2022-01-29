<?php

use App\Constant\ConversionServiceProvider;

return [
    'service_consumed_email_to' => env('BIZ_SET_SC_EMAIL_TO'),
    'conversion_rate_update_frequency' => 6*60*60,
    'conversion_rate_update_by' => ConversionServiceProvider::FIXER,
    'trx_list_pagination_count' => 10,
    'statement_list_pagination_count' => 10,

    'transaction' => [
        'send_money' => [
            "min" => env('SEND_MONEY_TRANSACTION_MIN_AMOUNT', 1),
            "max" => env('SEND_MONEY_TRANSACTION_MAX_AMOUNT', 100000000)
        ]
    ],
    'duplicate_trx_time_threshold' => 120,


];
