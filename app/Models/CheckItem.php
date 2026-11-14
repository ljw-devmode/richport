<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckItem extends Model
{
    protected $table = 'check_item';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'section_idx',  // 섹션 (check_section.idx)
    	'd2_name',      // 2depth 이름 (카테고리)
        'd3_name',      // 3depth 이름 (카테고리)
        'item_name',    // 아이템 이름 (질문, 체크항목)
        'input_type',   // 입력 타입 (radio, check, input, textarea)
        'sort_order',   // 아이템 순서
        'has_text',     // 비고란 표시 (기본값 N, Y일때 표시)
    ];

    protected $attributes = [
        'has_text' => 'N',
    ];    

    // item 하위 option
    public function checkoption() {
        return $this->hasMany(CheckOption::class, 'item_idx', 'idx')->orderBy('sort_order');
    }
}