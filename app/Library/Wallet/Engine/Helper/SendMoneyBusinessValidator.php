<?php

declare(strict_types=1);

namespace App\Library\Wallet\Engine\Helper;

use App\Exceptions\Wallet\Transaction\Transaction\BusinessValidationEx;
use App\Library\Common\BaseBusinessValidator;

class SendMoneyBusinessValidator extends BaseBusinessValidator
{
    private $senderWallet;
    private $receiverWallet;
    private $amount;
    private $sender_total;

    public function __construct($senderWallet, $receiverWallet, $amount, $sender_total)
    {
        $this->senderWallet = $senderWallet;
        $this->receiverWallet = $receiverWallet;
        $this->amount = $amount;
        $this->sender_total = $sender_total;
    }

    public function validate(): void
    {
        if ($this->isWalletActive($this->senderWallet) === false) {
            throw new BusinessValidationEx(trans('messages.sender_wallet_not_active'));
        }

        if ($this->isWalletActive($this->receiverWallet) === false) {
            throw new BusinessValidationEx(trans('messages.receiver_wallet_not_active'));
        }

        if ($this->hasSufficientBalance($this->senderWallet, $this->sender_total) === false) {
            throw new BusinessValidationEx(trans('messages.not_sufficient_amount_to_transfer'));
        }

        if ($this->checkDuplicateTransaction($this->senderWallet, $this->receiverWallet, $this->amount) === false) {
            throw new BusinessValidationEx(trans('messages.duplicate_trx'));
        }
    }
}
