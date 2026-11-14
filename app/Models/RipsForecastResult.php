<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * RIPS AI 재고 예측 결과 (상품 · 기준일별 1행)
 * AI 서버가 POST /ai-api/v1/forecasts 로 보낸 값을 저장, RIPS 화면은 RipsForecast::apply() 에서 사용
 */
class RipsForecastResult extends Model
{
    protected $table = 'rips_forecast';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'partner_idx',				// RIPS 업체
    		'product_idx',				// 업체 상품 (rips_product.idx)
    		'base_date',				// 예측 기준일
    		'expect_out_date',			// 잔여소진 예상일
    		'recommend_order_date',		// 추천 발주일
    		'recommend_qty',			// 추천 발주수량
    		'daily_avg',				// 예측 일평균 판매량 (참고)
    		'confidence',				// 예측 신뢰도 0~1 (참고)
    		'model_version',			// AI 모델 버전
    		'created_at',
    		'updated_at',
    ];

    protected $casts = [
        'base_date'            => 'date',
        'expect_out_date'      => 'date',
        'recommend_order_date' => 'date',
        'created_at'           => 'datetime',
        'updated_at'           => 'datetime',
    ];

    public function product()
    {
    	return $this->belongsTo(RipsProduct::class, 'product_idx', 'idx');
    }
}
