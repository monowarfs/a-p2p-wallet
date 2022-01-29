<?php

declare(strict_types=1);

namespace App\Constant;

class APIEndpoints
{
    public const VERSION = '/api/v1/version';
    public const V1_AUTH_SIGN_IN = '/api/v1/auth/login';
    public const V1_AUTH_REFRESH_TOKEN = '/api/v1/private/auth/refresh-token';
    public const V1_USER_WALLET_INFORMATION = '/api/v1/private/user/wallet-information';
    public const V1_SEND_MONEY_STEP1 = '/api/v1/private/transaction/send-money/summary';
    public const V1_SEND_MONEY_STEP2 = '/api/v1/private/transaction/send-money/execute';
    public const V1_USER_TRANSACTION_HISTORY = '/api/v1/private/user/transaction/history';
    public const V1_USER_STATEMENTS = '/api/v1/private/user/wallet/statements';
}
