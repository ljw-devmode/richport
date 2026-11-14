<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * AI API 요청 로그 (App\Http\Middleware\AiApiAuth 가 기록)
 */
class AiApiLog extends Model
{
    protected $table = 'ai_api_log';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = ['method', 'endpoint', 'status_code', 'result', 'item_count', 'message', 'ip', 'created_at'];

    protected $casts = ['created_at' => 'datetime'];
}
