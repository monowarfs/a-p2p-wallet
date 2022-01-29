<?php

declare(strict_types=1);

namespace App\Library\Wallet\Engine;

use App\Exceptions\Wallet\Transaction\Transaction\BusinessValidationEx;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Currency;
use App\Models\Statement;
use Illuminate\Support\Str;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Library\CurrencyConverter;
use Illuminate\Support\Facades\Log;
use App\Notifications\Wallet\Transaction\SendMoney\Received;
use App\Notifications\Wallet\Transaction\SendMoney\Sent;
use App\Library\Wallet\Engine\Helper\SendMoneyBusinessValidator;

class WalletEngine
{
    private string $senderMobileNo;
    private string $receiverMobileNo;
    private $amount;
    private $remarks;

    private $sender_total;

    private User $sender;
    private User $receiver;

    private Wallet $senderWallet;
    private Wallet $receiverWallet;

    private Currency $senderCurrency;
    private Currency $receiverCurrency;

    private CurrencyConverter $currencyConverter;

    public function __construct()
    {
        \Log::info("WalletEngine Class Constructor");
    }

    public function inputs($senderMobileNo, $receiverMobileNo, $amount, $remarks = null)
    {
        try {
            $this->remarks = $remarks;

            $this->getSender($senderMobileNo);

            $this->getReceiver($receiverMobileNo);

            $this->amount = $amount;

            $this->sender_total = $amount; // as no charges or fees applied yet

            $this->currencyConverter = (new CurrencyConverter(
                $this->senderCurrency->id,
                $this->receiverCurrency->id,
                $amount
            ));
        } catch (\Exception|\Throwable $e) {
            throw new \Exception($e->getMessage(), 0, $e);
        }
    }


    public function sendMoneyExecute()
    {
        try {

            (new SendMoneyBusinessValidator(
                $this->senderWallet,
                $this->receiverWallet,
                $this->amount,
                $this->sender_total
            ))
                ->validate();

            $transaction = $this->doTransaction();

            return [
                'summary' => [
                    'receiver' => [
                        'user' => [
                            'name' => $this->receiver->name,
                            'email' => $this->receiver->email,
                            'mobile_number' => $this->receiver->mobile_no,
                        ],
                    ],
                    'conversion_rate' => number_format(
                        $this->currencyConverter->getRate(),
                        6
                    ),
                    'amount' => number_format((float)$this->amount, 2),
                    'charge' => number_format(0.0, 2),
                    'total_payable' => number_format((float)$this->amount, 2),
                    'total_receivable' => number_format(
                        (float)$this->currencyConverter->getConvertedAmount(),
                        2
                    ),
                    'invoice_id' => $transaction->tx_unique_id,
                ],
            ];
        } catch (BusinessValidationEx $e) {
            Log::error(
                $e->getFile() . ' ' .
                $e->getLine() . ' ' .
                $e->getMessage()
            );
            throw new BusinessValidationEx($e->getMessage(), 0, $e);

        } catch (\Exception|\Throwable $e) {
            Log::error(
                $e->getFile() . ' ' .
                $e->getLine() . ' ' .
                $e->getMessage()
            );
            throw new \Exception($e->getMessage(), 0, $e);
        }
    }

    public function getSendMoneySummary()
    {
        try {
            (new SendMoneyBusinessValidator(
                $this->senderWallet,
                $this->receiverWallet,
                $this->amount,
                $this->sender_total
            ))
                ->validate();

            return [
                'summary' => [
                    'sender' => [
                        'user' => [
                            'name' => $this->sender->name,
                            'email' => $this->sender->email,
                            'mobile_number' => $this->sender->mobile_no,
                        ],
                        'wallet' => [
                            'ac_no' => $this->senderWallet->wallet_ac_no,
                        ],
                        'currency' => [
                            'id' => $this->senderCurrency->id,
                            'name' => $this->senderCurrency->name,
                            'code' => $this->senderCurrency->code,
                        ],
                    ],
                    'receiver' => [
                        'user' => [
                            'name' => $this->receiver->name,
                            'email' => $this->receiver->email,
                            'mobile_number' => $this->receiver->mobile_no,
                        ],
                        'wallet' => [
                            'ac_no' => $this->receiverWallet->wallet_ac_no,
                        ],
                        'currency' => [
                            'id' => $this->receiverCurrency->id,
                            'name' => $this->receiverCurrency->name,
                            'code' => $this->receiverCurrency->code,
                        ],
                    ],
                    'conversion_rate' => number_format(
                        $this->currencyConverter->getRate(),
                        6
                    ),
                    'amount' => number_format((float)$this->amount, 2),
                    'charge' => number_format(0.0, 2),
                    'total_payable' => number_format((float)$this->amount, 2),
                    'total_receivable' => number_format(
                        (float)$this->currencyConverter->getConvertedAmount(),
                        2
                    ),
                ],
            ];
        } catch (BusinessValidationEx $e) {
            Log::error(
                $e->getFile() . ' ' .
                $e->getLine() . ' ' .
                $e->getMessage()
            );
            throw new BusinessValidationEx($e->getMessage(), 0, $e);

        } catch (\Exception|\Throwable $e) {
            Log::error(
                $e->getFile() . ' ' .
                $e->getLine() . ' ' .
                $e->getMessage()
            );
            throw new \Exception($e->getMessage(), 0, $e);
        }
    }

    public function doTransaction()
    {
        try {
            DB::beginTransaction();

            // create the transaction entry
            $transaction = new Transaction();
            $transaction->tx_unique_id = Str::random(24);
            $transaction->sender_wallet_id = $this->senderWallet->id;
            $transaction->receiver_wallet_id = $this->receiverWallet->id;
            $transaction->sender_currency_id = $this->senderCurrency->id;
            $transaction->receiver_currency_id = $this->receiverCurrency->id;
            $transaction->amount = $this->amount;
            $transaction->sender_fee = 0.0;
            $transaction->sender_tax = 0.0;
            $transaction->conversion_charge = 0.0;
            $transaction->sender_total = $this->amount;
            $transaction->conversion_rate = $this->currencyConverter->getRate();
            $transaction->receiver_total = $this->currencyConverter->getConvertedAmount();
            $transaction->references = json_encode($this->currencyConverter->getRateObjects(), JSON_THROW_ON_ERROR);
            $transaction->remarks = $this->remarks;
            $transaction->save();

            //update the balance of sender + receiver
            $senderWallet = Wallet::lockForUpdate()->find($this->senderWallet->id);
            $receiverWallet = Wallet::lockForUpdate()->find($this->receiverWallet->id);

            $totalDeductable = $this->amount +
                $transaction->sender_fee +
                $transaction->sender_tax +
                $transaction->conversion_charge;

            $senderWallet->decrement('balance', $totalDeductable);
            $receiverWallet->increment('balance', $transaction->receiver_total);

            // create the statements table
            $data = [
                'user_id' => $this->sender->id,
                'wallet_id' => $senderWallet->id,
                'transaction_id' => $transaction->id,
                'description' => "{$this->senderCurrency->code} {$totalDeductable} has been transferred to {$this->receiver->mobile_no}",
                'debit' => $totalDeductable,
                'credit' => 0.0,
                'current_balance' => $senderWallet->balance,
                'created_at' => $transaction->created_at,
                'updated_at' => $transaction->created_at,
            ];
            Statement::create($data);

            $data2 = [
                'user_id' => $this->receiver->id,
                'wallet_id' => $receiverWallet->id,
                'transaction_id' => $transaction->id,
                'description' => "You've received {$this->receiverCurrency->code} " . number_format((float)$transaction->receiver_total, 2) . " from {$this->sender->mobile_no}",
                'debit' => 0.0,
                'credit' => $transaction->receiver_total,
                'current_balance' => $receiverWallet->balance,
                'created_at' => $transaction->created_at,
                'updated_at' => $transaction->created_at,
            ];
            Statement::create($data2);

            DB::commit();

            // send notification email
            $this->sentNotification($transaction, $senderWallet->balance);

            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error(
                $e->getFile() . ' ' .
                $e->getLine() . ' ' .
                $e->getMessage()
            );

            throw new \Exception($e->getMessage(), 0, $e);
        }
    }

    private function sentNotification($transaction, $senderCurrentBalance): void
    {
        $senderCurrencyCode = $this->senderCurrency->code;
        $senderAmount = number_format((float)$transaction->sender_total, 2);

        $this->sender->notify(new Sent(
            ['mail'],
            'A transaction equivalent to ' .
            $senderCurrencyCode . ' ' . $senderAmount . ' was made using your wallet account no ' .
            $this->senderWallet->wallet_ac_no . ' at ' . $this->receiver->name . '(' . $this->receiver->mobile_no . ') on ' .
            $transaction->created_at->format('Y-m-d') . '. If you have not made this transaction, please immediately call our Call Center. Your available balance is now ' . $senderCurrencyCode . ' ' . $senderCurrentBalance
        ));

        $this->receiver->notify(new Received(
            ['mail'],
            "You've received {$this->receiverCurrency->code} " . number_format((float)$transaction->receiver_total, 2) . " from {$this->sender->mobile_no}"
        ));
    }

    private function fetchUser(string $mobileNumber)
    {
        $user = User::where('mobile_no', '=', $mobileNumber)
            ->with('wallet.currency')
            ->first();

        if (!$user) {
            throw new \Exception('User Not Found');
        }
        return $user;
    }

    private function getSender($senderMobileNo): void
    {
        $this->senderMobileNo = $senderMobileNo;
        $this->sender = $this->fetchUser($senderMobileNo);
        $this->senderWallet = $this->sender->wallet;
        $this->senderCurrency = $this->sender->wallet->currency;
    }

    private function getReceiver($receiverMobileNo): void
    {
        $this->receiverMobileNo = $receiverMobileNo;
        $this->receiver = $this->fetchUser($receiverMobileNo);
        $this->receiverWallet = $this->receiver->wallet;
        $this->receiverCurrency = $this->receiver->wallet->currency;
    }
}
