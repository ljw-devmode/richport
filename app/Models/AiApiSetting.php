<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * AI 연동 설정 (1행) - 관리자 > RIPS 관리 > AI 연동
 */
class AiApiSetting extends Model
{
    protected $table = 'ai_api_setting';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    // 화면 예측값 사용 방식
    const MODES = [
    		'auto' => 'AI 값 우선 (없거나 유효기간이 지나면 계산식)',
    		'ai'   => 'AI 값만 (없으면 표시 안 함)',
    		'calc' => '계산식만 (AI 값 사용 안 함)',
    ];

    protected $fillable = [
    		'api_use',			// API 사용여부
    		'api_key',			// 인증키 (Bearer)
    		'forecast_mode',	// auto | ai | calc
    		'valid_days',		// AI 예측값 유효기간 (일)
    		'allowed_ips',		// 허용 IP (쉼표 구분)
    		'last_request_at',
    		'last_push_at',
    		'created_at',
    		'updated_at',
    		'updated_by',
    ];

    protected $hidden = ['api_key'];

    protected $casts = [
        'last_request_at' => 'datetime',
        'last_push_at'    => 'datetime',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    private static ?self $cached = null;

    // 현재 설정 (요청 안에서 1번만 조회, 테이블이 없거나 비어 있으면 기본값)
    public static function current(): self
    {
    	if (self::$cached) return self::$cached;
    	try {
    		$row = static::query()->orderBy('idx')->first();
    	} catch (\Throwable $e) {
    		$row = null;
    	}
    	return self::$cached = $row ?? new static(['api_use' => 0, 'forecast_mode' => 'calc', 'valid_days' => 3]);
    }

    public static function forget(): void
    {
    	self::$cached = null;
    }

    // 허용 IP 확인 (비어 있으면 모두 허용)
    public function ipAllowed(?string $ip): bool
    {
    	$list = array_filter(array_map('trim', explode(',', (string) $this->allowed_ips)));
    	return !$list || in_array($ip, $list, true);
    }
}
