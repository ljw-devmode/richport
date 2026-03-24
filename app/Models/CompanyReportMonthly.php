<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyReportMonthly extends Model
{
    protected $table = 'company_reportmonthly';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'group_idx',	// SPC 그룹 (companygroup.idx)
    		'comp_idx',		// 발전소 (comopany.idx)
			'tgt_year',		// 대상 년도 (yyyy)
    		'tgt_month',	// 대상 월 (m)
			'period', 		// 대상 년차 (대상 yyyy, m 이 해당하는 년차)
			'tgt_kwh',		// 목표 발전량 (company_yearkwhhours.tgt_hours * company.volumn)
			'act_kwh', 		// 실제 발전량 (companykwh 의 해당 년월의 act_kwh)
			'tgt_yearkwh',	// 년차별 목표 발전량 (company_yearkwh)
			'tgt_hours',	// 목표 발전 시간 (company_yearkwhhours.tgt_hours)
			'act_hours', 	// 실제 발전 시간 (act_kwh / company.volumn / 해당월의 날짜수)
			'total_rate',	// PR 종합 효율 (act_kwh / cur_month_mka * company.volumn)
			'act_rate', 	// PR 달성율 (total_rate / 83%)
			'pre_year_kma',	// 전년도 일사량 ()
			'pre_month_kma',// 전월 일사량
			'cur_month_kma',// 당월 일사량
			'total_sales',	// 총 매출액
			'smp_sales', 	// 총 smp 매출액
			'rec_sales',	// 총 rec 매출액
			'man_sales',	// 총 운영비 매출액
    		'created_at',
    		'updated_at'
    ];
    
    public function company()
    {
    	return $this->belongsTo(Company::class, 'comp_idx','idx');
    }

    public function companygroup()
    {
    	return $this->belongsTo(CompanyGroup::class, 'group_idx','idx');
    }	
}