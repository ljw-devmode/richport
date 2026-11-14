<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'department';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'depart_name',  // 부서명
    	'use_yn',       // 사용 여부 (Y/N)
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];	
    
    // 부서 활성 상태만 조회하는 스코프 (편의 기능)
    public function scopeActive($query)
    {
        return $query->where('use_yn', 'Y');
    }

    // 한 부서에는 여러 템플릿이 존재함
    public function checktemplate()
    {
        return $this->hasMany(CheckTemplate::class, 'dept_idx', 'idx');
    }

    // 한 부서에는 여러 카테고리가 존재함
    public function category()
    {
        return $this->hasMany(Category::class, 'depart_idx', 'idx');
    }
}