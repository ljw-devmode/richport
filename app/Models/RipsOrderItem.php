<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RipsOrderItem extends Model
{
    protected $table = 'rips_order_item';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'order_idx',		// 발주
    		'product_idx',		// 상품
    		'category_idx',		// 카테고리
    		'company_name',		// 업체명 (발주 시점)
    		'product_name',		// 상품명 (발주 시점)
    		'unit_price',		// 단가 (발주 시점)
    		'qty',				// 수량
    		'amount',			// 발주금액
    		'rp_product_idx',	// 리치포트 상품 (발주 정보 저장 시점 매핑)
    		'supplier_idx',		// 리치포트 발주 업체 (partner)
    		'purchase_date',	// 리치포트 발주일
    		'purchase_qty',		// 리치포트 발주 수량
    		'purchase_amount',	// 리치포트 발주 금액
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    public function richportProduct()
    {
    	return $this->belongsTo(Product::class, 'rp_product_idx', 'idx');
    }

    public function supplier()
    {
    	return $this->belongsTo(Partner::class, 'supplier_idx', 'idx');
    }

    public function order()
    {
    	return $this->belongsTo(RipsOrder::class, 'order_idx', 'idx');
    }

    public function product()
    {
    	return $this->belongsTo(RipsProduct::class, 'product_idx', 'idx');
    }

    public function category()
    {
    	return $this->belongsTo(RipsCategory::class, 'category_idx', 'idx');
    }
}
