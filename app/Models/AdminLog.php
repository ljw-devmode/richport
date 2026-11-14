<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 관리자 작업 로그 (등록 / 수정 / 삭제)
 * App\Http\Middleware\AdminActionLog 가 자동 기록, 서비스 관리 > 관리자 로그 에서 조회
 */
class AdminLog extends Model
{
    protected $table = 'admin_log';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    const ACTIONS = [
    		'create' => '등록',
    		'update' => '수정',
    		'delete' => '삭제',
    		'download' => '다운로드',
    ];

    const RESULTS = [
    		'success' => '성공',
    		'fail'    => '실패',
    ];

    protected $fillable = [
    		'admin_idx',
    		'admin_id',
    		'admin_name',
    		'menu',
    		'action',
    		'action_detail',
    		'target_idx',
    		'target_name',
    		'route_name',
    		'method',
    		'url',
    		'request_data',
    		'result',
    		'result_message',
    		'ip',
    		'user_agent',
    		'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function getActionTextAttribute()
    {
    	return self::ACTIONS[$this->action] ?? $this->action;
    }
}
