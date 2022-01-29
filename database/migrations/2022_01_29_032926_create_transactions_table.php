<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('tx_unique_id', 24)->unique();
            $table->foreignId('sender_wallet_id')->constrained('wallets');
            $table->foreignId('receiver_wallet_id')->constrained('wallets');
            $table->foreignId('sender_currency_id')->constrained('currencies');
            $table->foreignId('receiver_currency_id')->constrained('currencies');
            $table->decimal('amount', 16,4);
            $table->decimal('sender_fee', 16,4)->default(0.0);
            $table->decimal('sender_tax', 16,4)->default(0.0);
            $table->decimal('conversion_charge', 16,4)->default(0.0);
            $table->decimal('sender_total');
            $table->decimal('conversion_rate');
            $table->decimal('receiver_total');
            $table->json('references')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
