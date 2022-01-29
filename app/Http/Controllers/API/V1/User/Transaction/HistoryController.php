<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\User\Transaction;

use App\Http\Controllers\APIBaseController;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HistoryController extends APIBaseController
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $per_page = $this->getPaginationValue();
            $total_count = 0;
            $has_next_page = false;
            $transactions = $this->fetchTransactionHistory(
                $request,
                $per_page,
                $total_count,
                $has_next_page
            );

            return $this->respondInJSONWithAdditional(200, [], [
                'transactions' => $transactions,
                'has_next_page' => $has_next_page,
            ], $per_page, $total_count);
        } catch (\Exception | \Throwable $e) {
            Log::error($e);
            Log::error(
                $e->getFile() . ' ' .
                $e->getLine() . ' ' .
                $e->getMessage()
            );

            return $this->exceptionResponse(
                trans('messages.internal_server_error')
            );
        }
    }

    private function getPaginationValue(): int
    {
        $val = config('biz_settings.trx_list_pagination_count');

        if (($val === null) || ($val < 1)) {
            return 10;
        }

        return $val;
    }

    private function fetchTransactionHistory(
        Request $request,
        &$per_page,
        &$total_count,
        &$has_next_page
    ) {
        $transactionList = [];

        $wallet = Wallet::select('id')
            ->where('user_id', auth()->user()->id)
            ->first();

        $transactions = Transaction::with(
            'senderWallet.user',
            'receiverWallet.user',
            'senderCurrency',
            'receiverCurrency',
        );

        $this->applyFilter($transactions, $request, $wallet);

        $total_count = $transactions->count();

        $transactions = $transactions
            ->orderBy('created_at', 'DESC')
            ->paginate($per_page);

        $has_next_page = $transactions->nextPageUrl() ? true : false;

        foreach ($transactions as $transaction) {
            if (! $transaction->senderWallet->user) {
                continue;
            }

            if (! $transaction->receiverWallet->user) {
                continue;
            }

            if ($transaction->sender_wallet_id === $wallet->id) {
                $source = null;
                $destination = [
                    'name' => $transaction->receiverWallet->user->name ?? '',
                    'mobile_no' => $transaction
                        ->receiverWallet->user->mobile_no ?? '',
                ];
            } else {
                $destination = null;
                $source = [
                    'name' => $transaction->senderWallet->user->name ?? '',
                    'mobile_no' => $transaction->senderWallet->user
                        ->mobile_no ?? '',
                ];
            }

            $transactionList[] = [
                'title' => $transaction->sender_wallet_id === $wallet->id
                    ? 'Money Sent'
                    : 'Received Money',

                'source' => $source,
                'destination' => $destination,
                'sender_currency' => [
                    'id' => $transaction->senderCurrency->id,
                    'code' => $transaction->senderCurrency->code,
                    'name' => $transaction->senderCurrency->name,
                ],
                'receiver_currency' => [
                    'id' => $transaction->receiverCurrency->id,
                    'code' => $transaction->receiverCurrency->code,
                    'name' => $transaction->receiverCurrency->name,
                ],
                'trx_no' => 'Trans ID: ' . $transaction->tx_unique_id,

                'amount' => $transaction->sender_wallet_id === $wallet->id
                    ?
                    '- ' . number_format((float)$transaction->amount, 2)
                    :
                    '+ ' . number_format((float)$transaction->receiver_total, 2),

                'fee' => $transaction->sender_wallet_id === $wallet->id
                        ? number_format((float)$transaction->sender_fee, 2)
                        : '',

                'sender_tax' => $transaction->sender_wallet_id === $wallet->id
                        ? number_format((float)$transaction->sender_tax, 2)
                        : '',

                'conversion_charge' => $transaction->sender_wallet_id === $wallet->id
                        ? number_format((float)$transaction->conversion_charge, 2)
                        : '',

                'conversion_rate' => $transaction->sender_wallet_id === $wallet->id
                        ? number_format((float)$transaction->conversion_rate, 6)
                        : 'NA',

                'sender_total' => number_format((float)$transaction->sender_total, 2),

                'type_of_tx' => $transaction->sender_wallet_id === $wallet->id
                    ? 'Debit'
                    : 'Credit',

                'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),

                'remarks' => $transaction->remarks ?? '',

            ];
        }

        return $transactionList;
    }

    private function applyFilter(&$transactions, $request, $wallet): void
    {
        if ($request->filled('transaction_type')) {
            $transactions->where(function ($query) use ($wallet, $request): void {
                if ($request->transaction_type === 'in') {
                    $query->where('receiver_wallet_id', $wallet->id);
                } else {
                    $query->where('sender_wallet_id', $wallet->id);
                }
            });
        } else {
            $transactions->where(function ($query) use ($wallet): void {
                $query->where('sender_wallet_id', $wallet->id)
                    ->orWhere('receiver_wallet_id', $wallet->id);
            });
        }

        if ($request->filled('id')) {
            $transactions->where('tx_unique_id', 'LIKE', '%' . $request->id . '%');
        }

        if ($request->filled('tx_with')) {
            $tx_with = trim($request->tx_with);

            $tx_with = Str::start($tx_with, '+');

            $transactions->where(function ($query) use ($tx_with): void {
                $query->whereHas(
                    'senderWallet.user',
                    function ($query) use ($tx_with): void {
                        $query->where('mobile_no', $tx_with);
                    }
                )->orWhereHas(
                    'receiverWallet.user',
                    function ($query) use ($tx_with): void {
                        $query->where('mobile_no', $tx_with);
                    }
                );
            });
        }

        if ($request->filled('amount')) {
            $transactions->where('amount', $request->amount);
        }

        if (
            $request->filled('from_date') &&
            $request->filled('to_date')) {
            $transactions->whereBetween(
                'created_at',
                [$request->from_date, $request->to_date]
            );
        }
    }
}
