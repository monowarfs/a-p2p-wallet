<?php

use App\Constant\APIEndpoints as EP;
use App\Http\Controllers\API\V1\Auth\LogIn\LoginController;
use App\Http\Controllers\API\V1\Auth\RefreshTokenController;
use App\Http\Controllers\API\V1\Transaction\SendMoney\ExecuteController;
use App\Http\Controllers\API\V1\Transaction\SendMoney\SummaryController;
use App\Http\Controllers\API\V1\User\Transaction\HistoryController;
use App\Http\Controllers\API\V1\User\Transaction\StatementController;
use App\Http\Controllers\API\V1\User\WalletInfoController;
use App\Http\Controllers\API\V1\VersionController;
use Illuminate\Support\Facades\Route;


Route::get(EP::VERSION, [VersionController::class]);
Route::post(EP::V1_AUTH_SIGN_IN, [LoginController::class]);

Route::group([
    'middleware' => ['auth:api_auth_passport'],
], function () {

    Route::get(EP::V1_AUTH_REFRESH_TOKEN, [RefreshTokenController::class]);
    Route::get(EP::V1_USER_WALLET_INFORMATION, [WalletInfoController::class]);

    Route::get(EP::V1_WALLET_TRANSACTION_SEND_MONEY_STEP1, [SummaryController::class]);
    Route::get(EP::V1_WALLET_TRANSACTION_SEND_MONEY_STEP2, [ExecuteController::class]);

    Route::get(EP::V1_USER_TRANSACTION_HISTORY, [HistoryController::class]);
    Route::get(EP::V1_USER_STATEMENTS, [StatementController::class]);

});
