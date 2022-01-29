<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'transactions';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $guarded = ['id'];

    public function senderWallet(): BelongsTo
    {
        return $this->belongsTo(
            Wallet::class,
            'sender_wallet_id',
            'id'
        );
    }

    public function receiverWallet(): BelongsTo
    {
        return $this->belongsTo(
            Wallet::class,
            'receiver_wallet_id',
            'id'
        );
    }

    public function senderCurrency(): BelongsTo
    {
        return $this->belongsTo(
            Currency::class,
            'sender_currency_id',
            'id'
        );
    }

    public function receiverCurrency(): BelongsTo
    {
        return $this->belongsTo(
            Currency::class,
            'receiver_currency_id',
            'id'
        );
    }

    public function statements(): HasMany
    {
        return $this->hasMany(Statement::class);
    }
}
