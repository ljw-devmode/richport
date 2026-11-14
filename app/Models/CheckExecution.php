<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckExecution extends Model
{
    protected $table = 'check_execution';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    // 응답자 연령대 / 성별
    const AGE_GROUPS = [
        '1-10'  => '1~10세',
        '11-20' => '11~20세',
        '21-30' => '21~30세',
        '31-40' => '31~40세',
        '41-50' => '41~50세',
        '51-59' => '51~59세',
        '60+'   => '60세 이상',
    ];

    const GENDERS = [
        'M' => '남성',
        'F' => '여성',
    ];

    protected $fillable = [
        'parent_idx',   // 체크 결과 수정시 원본 결과 idx (수정시에만 값을 입력함)
    	'list_idx',     // 체크리스트 (check_list.idx)
        'project_idx',  // 프로젝트 idx (company.idx)
        'project_name', // 프로젝트명
        'user_comp',    // 체크한 사용자 회사 (입력)
        'user_name',    // 체크한 사용자 성명 (입력)
        'user_phone',   // 체크한 사용자 전화번호 (입력)
        'user_email',   // 체크한 사용자 이메일 (입력)
        'user_lat',     // 체크한 사용자 위도 (입력)
        'user_lng',     // 체크한 사용자 경도 (입력)
        'user_sign',    // 서명 이미지 위치
        'age_group',    // 응답자 연령대 (AGE_GROUPS)
        'gender',       // 응답자 성별 (GENDERS)
        'cities_idx',   // 응답자 거주지 시 (cities.idx)
        'districts_idx',// 응답자 거주지 구역 (districts.idx)
        'use_yn',       // 사용여부 (Y 사용, N 미사용)
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'        
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];        

    // 원본 idx 찾기
    public function parent() {
        return $this->belongsTo(CheckExecution::class, 'parent_idx', 'idx');
    }

    // 하위 idx 찾기
    public function children() {
        return $this->hasMany(CheckExecution::class, 'parent_idx', 'idx')->orderByDesc('created_at');
    }    

    // 체크리스트 하위 section
    public function checkresult() {
        return $this->hasMany(CheckResult::class, 'exec_idx', 'idx');
    }

    // 체크리스트
    public function checklist() { 
        return $this->belongsTo(CheckList::class, 'list_idx', 'idx'); 
    }   
    
    public function cities() {
        return $this->belongsTo(Cities::class, 'cities_idx', 'idx');
    }

    public function districts() {
        return $this->belongsTo(Districts::class, 'districts_idx', 'idx');
    }

    // 하위 서명 찾기
    public function checksigns() {
        return $this->hasMany(CheckSign::class, 'exec_idx', 'idx');
    }
    
}