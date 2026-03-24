<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyYearKwh extends Model
{
    protected $table = 'company_yearkwh';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'comp_idx',		// 발전소 (company.idx)
    		'period',       // 대상 년차 (company.start_date 의 yyyymm 을 기준으로 12개월단위로 년차 구분)
    		'year_kwh',     // 년차별 목표 발전량
            'start_year',   // 년차 시작월 (예 2025-09-15 > 202509 가 시작월이됨)
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