<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConversionRate extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'conversion_rates';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $guarded = ['id'];

    public function from(): BelongsTo
    {
        return $this->belongsTo(
            Currency::class,
            'from_id',
            'id'
        );
    }

    public function to(): BelongsTo
    {
        return $this->belongsTo(
            Currency::class,
            'to_id',
            'id'
        );
    }
}
