<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckSection extends Model
{
    protected $table = 'check_section';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    	'list_idx',     // 체크리스트 (check_list.idx)
    	'section_name', // 1depth 섹션 이름
        'sort_order',   // 섹션 순서
        'layout_type',  // depth (1, 2, 3 depth / 기본값 3), 1 -> section + 질문 / 2 -> section 구분 + 질문 / 3 -> section 구분 + 항목 + 질문
    ];

    protected $attributes = [
        'layout_type' => '3',
    ];

    // section 하위 item
    public function checkitem() {
        return $this->hasMany(CheckItem::class, 'section_idx', 'idx')->orderBy('sort_order');
    }
}