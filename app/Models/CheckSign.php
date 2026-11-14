<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckSign extends Model
{
    protected $table = 'check_sign';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
        'exec_idx',     // 체크 결과 idx (CheckExecution.idx)
        'user_name',    // 체크한 사용자 성명 (입력)
        'user_sign',    // 서명 이미지 위치 
    ];
    
    // 체크리스트
    public function checkexection() { 
        return $this->belongsTo(CheckExecution::class, 'exec_idx', 'idx'); 
    }    
}