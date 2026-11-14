<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasLocalizedFields;

    protected $table = 'faq';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'question',			// 질문(ko)
    		'question_en',
    		'question_vn',
    		'answer',			// 답변(ko)
    		'answer_en',
    		'answer_vn',
    		'orderby',			// 정렬순서 (작은 값이 위)
    		'use_yn',			// 노출여부 (0:미노출, 1:노출)
    		'created_at',
    		'created_by',
    		'updated_at',
    		'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // 사용자 화면 노출 목록
    public function scopeVisible($query)
    {
    	return $query->where('use_yn', 1)->orderBy('orderby')->orderBy('idx');
    }
}
