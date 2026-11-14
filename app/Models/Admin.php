<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // ← 이걸 상속해야 함
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
	use Notifiable;
	
    protected $table = 'admin';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'idx',
    	'id',
        'pw',           // 비밀번호 확인 미적용
        'name',
        'hp',
        'email',
        'level',        // 등급 1은 전체 관리자 (DB에서 수정필요), 관리자는 등록시 기본 2로 설정됨
        'created_at',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
    
    public function getAuthPassword()
    {
    	return $this->pw;
    }

    public function permissions()
    {
        // admin_permissions 테이블과 연결 (admin_idx 기준)
        return $this->hasMany(\App\Models\AdminPermissions::class, 'admin_idx', 'idx');
    }

    /**
     * 사용자가 특정 권한을 가졌는지 확인
     */
    public function hasPerm($key)
    {
        // 최고관리자(level 1)는 무조건 통과 (프리패스)
        if ($this->level == 1) return true;

        // 로그인 시 권한 리스트를 세션에 담아두면 성능이 훨씬 좋아집니다.
        // 여기서는 간단하게 pluck으로 확인하는 로직입니다.
        return $this->permissions->pluck('permission_key')->contains($key);
    }
}