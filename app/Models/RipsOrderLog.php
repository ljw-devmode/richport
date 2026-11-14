<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RipsOrderLog extends Model
{
    protected $table = 'rips_order_log';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'order_idx',		// 발주
    		'status',			// 변경된 상태
    		'prev_status',		// 이전 상태
    		'created_at',
    		'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function getStatusTextAttribute()
    {
    	return RipsOrder::STATUSES[$this->status] ?? $this->status;
    }
}
