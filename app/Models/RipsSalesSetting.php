<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RipsSalesSetting extends Model
{
    protected $table = 'rips_sales_setting';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'partner_idx',			// 파트너
    		'api_use',				// API 연동 사용
    		'api_key',				// API 인증키
    		'last_received_at',		// 마지막 수신일시
    		'created_at',
    		'updated_at'
    ];

    protected $casts = [
        'last_received_at' => 'datetime',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime'
    ];

    public function partner()
    {
    	return $this->belongsTo(Partner::class, 'partner_idx', 'idx');
    }
}
