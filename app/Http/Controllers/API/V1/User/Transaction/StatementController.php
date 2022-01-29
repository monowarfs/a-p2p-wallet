<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\User\Transaction;

use App\Http\Controllers\APIBaseController;
use App\Models\Statement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StatementController extends APIBaseController
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $per_page = $this->getPaginationValue();
            $total_count = 0;
            $has_next_page = false;
            $transactions = $this->fetchStatementHistory(
                $request,
                $per_page,
                $total_count,
                $has_next_page
            );

            return $this->respondInJSONWithAdditional(200, [], [
                'statements' => $transactions,
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
        $val = config('biz_settings.statement_list_pagination_count');

        if (($val === null) || ($val < 1)) {
            return 10;
        }

        return $val;
    }

    private function fetchStatementHistory(
        Request $request,
        &$per_page,
        &$total_count,
        &$has_next_page
    ) {
        $statementList = [];

        $statements = Statement::with('user')
            ->where('user_id', auth()->user()->id);

        $this->applyFilter($statements, $request);

        $total_count = $statements->count();

        $statements = $statements
            ->orderBy('created_at', 'DESC')
            ->paginate($per_page);

        $has_next_page = $statements->nextPageUrl() ? true : false;

        foreach ($statements as $statement) {
            $statementList[] = [
                'description' => $statement->tx_unique_id,
                'debit' => number_format((float)$statement->debit, 2),
                'credit' => number_format((float)$statement->credit, 2),
                'current_balance' => number_format((float)$statement->current_balance, 2),
                'created_at' => $statement->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return collect($statementList)
            ->sortBy('created_at')
            ->values()
            ->all();
    }

    private function applyFilter(&$statements, $request): void
    {
        if (
            $request->filled('from_date') &&
            $request->filled('to_date')) {
            $statements->whereBetween(
                'created_at',
                [$request->from_date, $request->to_date]
            );
        }
    }
}
