<?php

use App\Constant\ConversionServiceProvider;

return [
    'service_consumed_email_to' => env('BIZ_SET_SC_EMAIL_TO'),
    'conversion_rate_update_frequency' => 6*60*60,
    'conversion_rate_update_by' => ConversionServiceProvider::FIXER

];
