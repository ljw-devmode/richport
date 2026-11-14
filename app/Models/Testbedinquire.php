<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testbedinquire extends Model
{
    protected $table = 'testbed_inquire';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'status',
    		'types_idx',			// 신청 상품 (product.idx)
    		'partner_idx',			// 신청 RIPS 업체 (partner.idx), NULL : 홈페이지 신청
    		'inquire_date',
    		'inquire_company',
    		'inquire_hp',
    		'inquire_address',
    		'inquire_contents',		// 문의 사항
    		'response_contents',	// 답변 내용 (관리자 내부 처리내용, 업체 비공개)
    		'feedback',				// 피드백 (RIPS 업체에 공개)
    		'feedback_by',
    		'feedback_at',
    		'response_by',
    		'response_at',
    		'complete_at'
    ];
    
    // 처리상태
    const STATUS_PROGRESS = 4;		// 테스트베드 진행중
    const STATUS_DONE     = 5;		// 테스트베드 진행완료 (RIPS 업체에 설문조사 결과 공개)

    const STATUSES = [
    		1 => '접수',
    		2 => '답변준비중',
    		3 => '답변완료',
    		4 => '진행중',
    		5 => '진행완료',
    ];

    // 신청구분 (partner_idx 유무로 판단)
    const SOURCES = [
    		'homepage' => '홈페이지',
    		'rips'     => 'RIPS 업체',
    ];

    protected $appends = ['status_kr', 'source_kr'];

    public function getSourceKrAttribute()
    {
    	return $this->partner_idx ? self::SOURCES['rips'] : self::SOURCES['homepage'];
    }

    // 신청구분 검색 (homepage / rips)
    public function scopeSource($query, ?string $source)
    {
    	return match ($source) {
    		'homepage' => $query->whereNull('partner_idx'),
    		'rips'     => $query->whereNotNull('partner_idx'),
    		default    => $query,
    	};
    }
    
    public function getStatusKrAttribute()
    {
    	return self::STATUSES[(int) $this->status] ?? '알수없음';
    }
    
    public function product()
    {
    	return $this->belongsTo(Product::class, 'types_idx','idx');
    }

    public function partner()
    {
    	return $this->belongsTo(Partner::class, 'partner_idx', 'idx');
    }
}
