<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RipsOrder extends Model
{
    protected $table = 'rips_order';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    // 발주상태
    const STATUSES = [
    		'ordered'   => '발주접수',
    		'preparing' => '상품준비중',
    		'shipping'  => '물류진행중',
    		'customs'   => '통관진행중',
    		'received'  => '입고완료',
    		'canceled'  => '발주취소',
    ];

    /**
     * 업체 발주 목록 (RIPS > 발주관리, 관리자 > RIPS 업체 상세 > 발주 현황 공통)
     * @param array $filters start_date, end_date, status
     */
    public static function listFor(int $partnerIdx, array $filters, int $perPage = 10)
    {
    	$filled = fn ($key) => isset($filters[$key]) && $filters[$key] !== '';

    	return static::with('items')->where('partner_idx', $partnerIdx)
    	->when($filled('start_date'), fn ($q) => $q->whereDate('order_date', '>=', $filters['start_date']))
    	->when($filled('end_date'), fn ($q) => $q->whereDate('order_date', '<=', $filters['end_date']))
    	->when($filled('status'), fn ($q) => $q->where('status', $filters['status']))
    	->orderByDesc('order_date')->orderByDesc('idx')->paginate($perPage)->withQueryString();
    }

    protected $fillable = [
    		'partner_idx',		// 파트너
    		'order_no',			// 발주번호
    		'order_date',		// 발주일
    		'orderer_dept',		// 발주자 부서
    		'orderer_name',		// 발주자명
    		'orderer_tel',		// 연락처(전화)
    		'orderer_email',	// 연락처(이메일)
    		'total_items',		// 총 상품 가지수
    		'total_qty',		// 총 수량
    		'total_amount',		// 발주총액
    		'status',			// 발주상태
    		'expected_date',	// 예상입고일
    		'logistics_start',	// 물류 시작일 (관리자)
    		'logistics_end',	// 물류 종료일
    		'customs_start',	// 통관 시작일
    		'customs_end',		// 통관 종료일
    		'received_date',	// 입고일
    		'admin_memo',		// 관리자 메모
    		'sign_path',		// 서명 이미지
    		'canceled_at',
    		'created_at',
    		'created_by',
    		'updated_at',
    		'updated_by'
    ];

    protected $casts = [
        'order_date'    => 'datetime',
        'expected_date' => 'date',
        'logistics_start' => 'date',
        'logistics_end'   => 'date',
        'customs_start'   => 'date',
        'customs_end'     => 'date',
        'received_date'   => 'date',
        'canceled_at'   => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime'
    ];

    public function items()
    {
    	return $this->hasMany(RipsOrderItem::class, 'order_idx', 'idx')->orderBy('idx');
    }

    public function partner()
    {
    	return $this->belongsTo(Partner::class, 'partner_idx', 'idx');
    }

    // 상태 변경 이력 (최신순)
    public function logs()
    {
    	return $this->hasMany(RipsOrderLog::class, 'order_idx', 'idx')->orderByDesc('idx');
    }

    public function getStatusTextAttribute()
    {
    	return self::STATUSES[$this->status] ?? $this->status;
    }

    // 발주접수 상태에서만 수정/취소 가능
    public function isEditable()
    {
    	return $this->status === 'ordered';
    }
}
