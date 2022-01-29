<?php

declare(strict_types=1);

namespace App\Library\Wallet\Transaction;

use App\Exceptions\Wallet\Transaction\Transaction\BusinessValidationEx;
use App\Library\Wallet\Engine\WalletEngine;

class SendMoneyService
{
    private WalletEngine $walletEngine;
    private $senderMobileNo;
    private $receiverMobileNo;
    private $amount;
    private $remarks;

    public function __construct($senderMobileNo, $receiverMobileNo, $amount, $remarks = null)
    {
        $this->senderMobileNo = $senderMobileNo;
        $this->receiverMobileNo = $receiverMobileNo;
        $this->amount = $amount;
        $this->remarks = $remarks;
    }

    public function getSummary()
    {
        try {
            $this->walletEngine = new WalletEngine();
            $this->walletEngine->inputs(
                $this->senderMobileNo,
                $this->receiverMobileNo,
                $this->amount,
                $this->remarks
            );

            return $this->walletEngine->getSendMoneySummary();
        } catch (\Exception | \Throwable $e) {
            if($e instanceof BusinessValidationEx){
                throw new BusinessValidationEx($e->getMessage());
            }
            throw new \Exception($e->getMessage(), 0, $e);
        }
    }

    public function execute()
    {
        try {
            $this->walletEngine = new WalletEngine();
            $this->walletEngine->inputs(
                $this->senderMobileNo,
                $this->receiverMobileNo,
                $this->amount,
                $this->remarks
            );

            return $this->walletEngine->sendMoneyExecute();

        } catch (\Exception | \Throwable $e) {
            if($e instanceof BusinessValidationEx){
                throw new BusinessValidationEx($e->getMessage());
            }
            throw new \Exception($e->getMessage(), 0, $e);
        }
    }
}
