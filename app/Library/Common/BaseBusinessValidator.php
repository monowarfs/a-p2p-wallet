<?php

declare(strict_types=1);

namespace App\Library\Common;

use App\Models\Transaction;
use Carbon\Carbon;

class BaseBusinessValidator
{
    public function isWalletActive($wallet)
    {
        return $wallet->status === 1;
    }

    public function hasSufficientBalance($wallet, $amount)
    {
        if($wallet->balance < $amount) return false;
        return true;
    }

    protected function checkDuplicateTransaction($senderWallet, $receiverWallet, $amount)
    {
        $lastTrx = Transaction::where('sender_wallet_id', $senderWallet->id)
            ->where('receiver_wallet_id', $receiverWallet->id)
            ->where('amount', $amount)
            ->latest()
            ->first();

        if ($lastTrx) {
            $now = Carbon::now();
            $diffInSeconds = $now->diffInSeconds($lastTrx->updated_at);

            if ($diffInSeconds < config('biz_settings.duplicate_trx_time_threshold')) {
                return false;
            }
        }

        return true;
    }
}
