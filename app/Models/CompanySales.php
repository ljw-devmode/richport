<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySales extends Model
{
    protected $table = 'company_sales';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'comp_idx',		// 발전소 (company.idx)
			'tgt_year',		// 발전 / 매출 발생 년도 (yyyy)
			'tgt_month',	// 발전 / 매출 발생 월 (m)
			'supplier',		// 공급자
			'buyer',		// 공급 받는 자
			'day',			// 세금계산서 발행일 (세금계산서는 추후 발행이다보니 대상 년,월과 발행일이 다를 수 있음)
			'title',		// 세금계산서 품목명
    		'type',			// 구분 (REC, SMP, 운영비)
    		'quantity',		// 전력 판매량
			'unitprice',	// 판매 단가
    		'sales',		// 매출 = 전력 판매량 * 판매 단가
    		'supplyprice',	// 공급가 = 매출 / 1.1
    		'vat',			// 부가가치세 = 매출 - 공급가
			'memo',			// 비고
    		'created_by',
    		'created_at',
    		'updated_by',
    		'updated_at'
    ];
    
    public function company()
    {
    	return $this->belongsTo(Company::class, 'comp_idx','idx');
    }	
}