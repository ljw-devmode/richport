<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RipsSales extends Model
{
    protected $table = 'rips_sales';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    // 구분
    const INPUT_TYPES = [
    		'case'  => '건별',
    		'daily' => '일별합계',
    		'stock' => '재고등록',		// 판매 없이 잔여수량만 등록 (수량/금액 0)
    ];

    // 등록경로
    const SOURCES = [
    		'manual' => '직접입력',
    		'excel'  => '엑셀',
    		'api'    => 'API',
    ];

    // 프로모션
    const PROMOTIONS = [
    		'N'        => '없음',
    		'discount' => '할인',
    		'1+1'      => '1+1',
    		'2+1'      => '2+1',
    ];

    // 판매매대
    const DISPLAY_PLACES = [
    		''        => '미입력',
    		'basic'   => '기본상품코너',
    		'counter' => '계산대앞',
    		'event'   => '할인/이벤트코너',
    ];

    /**
     * 업체 판매 목록 (RIPS > 판매관리, 관리자 > RIPS 업체 상세 > 판매 현황 공통)
     * @param array $filters start_date, end_date, input_type, source, category_idx, keyword
     * @return array [paginator, 합계(cnt, qty, amount)]
     */
    public static function listFor(int $partnerIdx, array $filters, int $perPage = 20): array
    {
    	$filled = fn ($key) => isset($filters[$key]) && $filters[$key] !== '';

    	$query = static::with('product')->where('partner_idx', $partnerIdx)
    	->when($filled('start_date'), fn ($q) => $q->where('sales_date', '>=', $filters['start_date']))
    	->when($filled('end_date'), fn ($q) => $q->where('sales_date', '<=', $filters['end_date']))
    	->when($filled('input_type'), fn ($q) => $q->where('input_type', $filters['input_type']))
    	->when($filled('source'), fn ($q) => $q->where('source', $filters['source']))
    	->when($filled('category_idx'), fn ($q) => $q->whereIn('category_idx', RipsCategory::descendantIds($partnerIdx, $filters['category_idx'])))
    	->when($filled('keyword'), fn ($q) => $q->whereHas('product', fn ($p) => $p->where('product_name', 'like', '%'.$filters['keyword'].'%')));

    	$summary = (clone $query)->selectRaw('COUNT(*) AS cnt, COALESCE(SUM(qty),0) AS qty, COALESCE(SUM(total_amount),0) AS amount')->first();
    	$data    = $query->orderByDesc('sales_date')->orderByDesc('sales_time')->orderByDesc('idx')->paginate($perPage)->withQueryString();

    	return [$data, $summary];
    }

    protected $fillable = [
    		'partner_idx',		// 파트너
    		'product_idx',		// 상품
    		'category_idx',		// 카테고리
    		'input_type',		// 구분 (case:건별, daily:일별합계)
    		'source',			// 등록경로 (manual, excel, api)
    		'sales_date',		// 판매일자
    		'sales_time',		// 판매시각 (건별)
    		'qty',				// 총 판매수량
    		'total_amount',		// 총 판매가
    		'unit_price',		// 상품별 가격 (총 판매가 / 총 수량, 반올림)
    		'stock_qty',		// 잔여수량
    		'promotion',		// 프로모션
			'discount_rate',	// 할인율 (%, 할인일 때)
    		'display_place',	// 판매매대
    		'created_at',
    		'created_by',
    		'updated_at',
    		'updated_by'
    ];

    protected $casts = [
        'sales_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function product()
    {
    	return $this->belongsTo(RipsProduct::class, 'product_idx', 'idx');
    }

    public function category()
    {
    	return $this->belongsTo(RipsCategory::class, 'category_idx', 'idx');
    }

    /**
     * 상품별 가격 = 총 판매가 / 총 수량 (반올림)
     */
    public static function calcUnitPrice($totalAmount, $qty)
    {
    	return $qty > 0 ? (int) round($totalAmount / $qty) : 0;
    }
}
