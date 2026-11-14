<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RipsProduct extends Model
{
    protected $table = 'rips_product';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'partner_idx',				// 파트너
    		'category_idx',				// 카테고리
    		'product_idx',				// 리치포트 상품 매핑 (product.idx)
    		'company_name',				// 업체명 (제조/공급사)
    		'product_name',				// 상품명
    		'barcode',					// 바코드 (등록 시 필수)
    		'unit_price',				// 업체 입력 발주 단가
    		'price_type',				// 발주금액 (default:기본, custom:개별)
    		'custom_price',				// 개별 발주금액
    		'stock_qty',				// 잔여수량
    		'lead_days',				// 예약 입고 소요기간(일)
    		'expect_out_date',			// 잔여소진 예상일 (AI)
    		'recommend_order_date',		// 추천 발주일 (AI)
    		'recommend_qty',			// 추천수량 (AI)
    		'created_at',
    		'created_by',
    		'updated_at',
    		'updated_by'
    ];

    protected $casts = [
        'expect_out_date'      => 'date',
        'recommend_order_date' => 'date',
        'created_at'           => 'datetime',
        'updated_at'           => 'datetime'
    ];

    public function category()
    {
    	return $this->belongsTo(RipsCategory::class, 'category_idx', 'idx');
    }

    // 매핑된 리치포트 상품
    public function richportProduct()
    {
    	return $this->belongsTo(Product::class, 'product_idx', 'idx');
    }

    /**
     * 발주 시 적용되는 단가
     * 개별금액(입력 시) → 매핑된 리치포트 상품의 기본 발주금액 → 업체 입력 단가
     * @return array [단가, 적용기준(custom|default|partner)]
     */
    public function orderPrice()
    {
    	if ($this->price_type === 'custom' && $this->custom_price !== null) {
    		return [(int) $this->custom_price, 'custom'];
    	}
    	if ($this->product_idx && $this->richportProduct && $this->richportProduct->order_price !== null) {
    		return [(int) $this->richportProduct->order_price, 'default'];
    	}
    	return [(int) $this->unit_price, 'partner'];
    }

    public function sales()
    {
    	return $this->hasMany(RipsSales::class, 'product_idx', 'idx');
    }
}
