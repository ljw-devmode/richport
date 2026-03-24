<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyManage extends Model
{
    protected $table = 'company_manage';
    protected $primaryKey = 'idx';
    public $timestamps = false;

    protected $fillable = [
    		'comp_idx',		// 발전소 (company.idx)
    		'day',			// O&M, 이슈 발생일 (yyyy-mm-dd)
    		'type',			// 구분 (정기점검, 긴급점검, 소모품교체, 이슈)
    		'manage',		// 업무 내용
			'result',		// 처리 결과
			'count',		// 처리 수량
    		'del_yn',		// 삭제여부 (현재 미사용중)
    		'created_at',
			'created_by',
    		'updated_at',
			'updated_by'
    ];
    
    public function company()
    {
    	return $this->belongsTo(Company::class, 'comp_idx','idx');
    }	
}