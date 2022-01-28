<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HTTPRequestLog extends Model
{
    use HasFactory;
    protected $table = 'http_request_logs';
    protected $dates = ['created_at', 'updated_at'];
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
