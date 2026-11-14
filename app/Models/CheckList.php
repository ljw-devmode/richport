<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CheckList extends Model
{
    protected $table = 'check_list';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'check_name',   // 체크리스트 제목
    	'depart_idx',   // 부서 (department.idx)
        'product_idx',  // 테스트베드 상품 (product.idx)
        'survey_token', // 설문 URL 토큰 (/survey/{토큰})
        'use_yn',       // 사용 여부 (Y/N)
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];        

    protected static function booted()
    {
        static::creating(function ($list) {
            $list->survey_token = $list->survey_token ?: Str::random(32);
        });
    }

    // 테스트베드 상품
    public function product() {
        return $this->belongsTo(Product::class, 'product_idx', 'idx');
    }

    // list 하위 section
    public function checksection() {
        return $this->hasMany(CheckSection::class, 'list_idx', 'idx')->orderBy('sort_order');
    }

    // list 결과
    public function checkexecution() {
        return $this->hasMany(CheckExecution::class, 'list_idx', 'idx');
    }

    // list 결과
    public function department() {
        return $this->belongsTo(Department::class, 'depart_idx', 'idx');
    }  
}